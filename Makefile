.PHONY: up down build start stop restart logs shell artisan migrate seed fresh test deploy ps

%:
	$(MAKE) -C docker $@
