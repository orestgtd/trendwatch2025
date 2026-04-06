.PHONY: hello load positions realized_gains
.PHONY: test

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
