FROM python:3.12-slim

WORKDIR /workspace/python

RUN apt-get update && apt-get install -y \
    bash \
    build-essential \
    curl \
 && rm -rf /var/lib/apt/lists/*

COPY requirements.txt requirements-dev.txt .
RUN pip install --no-cache-dir -r requirements-dev.txt

EXPOSE 8888

CMD ["jupyter", "lab", "--ip=0.0.0.0", "--no-browser", "--allow-root"]
