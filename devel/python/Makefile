.PHONY: hello load
.PHONY: test

hello:
	python3 hello.py

test:
	python3 -m pytest -v

load:
	python3 loader/load_trades.py --file $(FILE)
