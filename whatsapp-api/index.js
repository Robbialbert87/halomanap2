const express = require('express');
const cors = require('cors');
const { Client, LocalAuth } = require('whatsapp-web.js');
const qrcodeTerminal = require('qrcode-terminal');
const QRCode = require('qrcode');

const app = express();
const port = 3000;

app.use(cors());
app.use(express.json());

let isReady = false;
let qrCodeDataURL = '';
let isAuthenticated = false;

let client = null;

function initializeClient() {
    console.log('⌛ Menginisialisasi WhatsApp Client...');
    isReady = false;
    qrCodeDataURL = '';
    isAuthenticated = false;

    const fs = require('fs');
    const commonPaths = [
        'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe',
        'C:\\Program Files (x86)\\Google\\Chrome\\Application\\chrome.exe',
        'C:\\Program Files\\Chromium\\Application\\chrome.exe',
        process.env.LOCALAPPDATA + '\\Google\\Chrome\\Application\\chrome.exe',
        process.env.PROGRAMFILES + '\\Google\\Chrome\\Application\\chrome.exe',
        process.env['PROGRAMFILES(X86)'] + '\\Google\\Chrome\\Application\\chrome.exe',
    ];
    const chromePath = commonPaths.find(p => fs.existsSync(p)) || process.env.PUPPETEER_EXECUTABLE_PATH;

    client = new Client({
        authStrategy: new LocalAuth(),
        puppeteer: {
            headless: true,
            executablePath: chromePath,
            args: [
                '--no-sandbox',
                '--disable-setuid-sandbox',
                '--disable-dev-shm-usage',
                '--disable-accelerated-2d-canvas',
                '--no-first-run',
                '--no-zygote',
                '--disable-gpu'
            ]
        }
    });

    client.on('qr', async (qr) => {
        console.log('\n--- SCAN QR CODE INI MENGGUNAKAN WHATSAPP ANDA ---');
        qrcodeTerminal.generate(qr, { small: true });
        console.log('--------------------------------------------------\n');
        
        // Generate base64 DataURL for Web GUI
        try {
            qrCodeDataURL = await QRCode.toDataURL(qr);
        } catch (err) {
            console.error('Gagal generate QR Code Base64:', err);
        }
    });

    client.on('ready', () => {
        isReady = true;
        qrCodeDataURL = ''; // Clear QR since it's connected
        isAuthenticated = true;
        console.log('✅ WhatsApp Client is READY!');
    });

    client.on('authenticated', () => {
        isAuthenticated = true;
        console.log('✅ WhatsApp Client Authenticated!');
    });

    client.on('auth_failure', msg => {
        console.error('❌ Authentication failure', msg);
        isAuthenticated = false;
        qrCodeDataURL = '';
    });

    client.on('disconnected', (reason) => {
        console.log('❌ WhatsApp Client was disconnected', reason);
        isReady = false;
        isAuthenticated = false;
        qrCodeDataURL = '';
        
        // If disconnected normally (e.g. user logged out from phone), re-initialize
        setTimeout(() => {
            initializeClient();
        }, 3000);
    });

    client.initialize();
}

// First time init
initializeClient();

// API Endpoint to send message
app.post('/send', async (req, res) => {
    if (!isReady) {
        return res.status(503).json({ success: false, error: 'WhatsApp Client is not ready yet.' });
    }

    const { number, message } = req.body;

    if (!number || !message) {
        return res.status(400).json({ success: false, error: 'Number and message are required.' });
    }

    try {
        let formattedNumber = number.replace(/\D/g, '');
        if (formattedNumber.startsWith('0')) {
            formattedNumber = '62' + formattedNumber.substring(1);
        }
        
        const chatId = `${formattedNumber}@c.us`;

        await client.sendMessage(chatId, message);
        
        console.log(`Pesan terkirim ke: ${formattedNumber}`);
        return res.status(200).json({ success: true, message: 'Message sent successfully.' });
    } catch (error) {
        console.error('Failed to send message:', error);
        return res.status(500).json({ success: false, error: error.message });
    }
});

// API Endpoint to check status and get QR
app.get('/status', (req, res) => {
    return res.json({
        success: true,
        isReady: isReady,
        isAuthenticated: isAuthenticated,
        qr: qrCodeDataURL
    });
});

// API Endpoint to reset/logout
app.post('/reset', async (req, res) => {
    console.log('🔄 Menerima perintah Reset dari Dashboard...');
    try {
        if (client) {
            // Attempt to logout if connected
            if (isReady || isAuthenticated) {
                await client.logout();
            } else {
                await client.destroy();
            }
        }
    } catch (error) {
        console.error('Error during client destruction/logout:', error);
    }
    
    // Reinitialize
    initializeClient();
    
    return res.json({ success: true, message: 'Client is being reset. Please wait a few seconds for new QR.' });
});

app.listen(port, () => {
    console.log(`🚀 WhatsApp Gateway API running at http://localhost:${port}`);
});
