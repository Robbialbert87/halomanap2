FROM node:22-slim

RUN apt-get update && apt-get install -y \
    chromium \
    chromium-sandbox \
    && rm -rf /var/lib/apt/lists/*

ENV PUPPETEER_SKIP_DOWNLOAD=true
ENV PUPPETEER_EXECUTABLE_PATH=/usr/bin/chromium

WORKDIR /app

COPY whatsapp-api/package.json whatsapp-api/package-lock.json ./
RUN npm ci

COPY whatsapp-api/ .

EXPOSE 3000
CMD ["node", "index.js"]
