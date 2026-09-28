.PHONY: expire load positions realized_gains
.PHONY: hello test
.PHONY: asof

ASOF ?= $(shell date +%Y-%m-%d)

expire:
	python3 expire.py --asof $(ASOF)

hello:
	python3 hello.py

test:
	python3 -m pytest -v

load:
	python3 loader/load_trades.py --file $(FILE)

positions:
	python3 positions.py

realized_gains:
	python3 realized_gains.py

asof:
	echo $(ASOF)
