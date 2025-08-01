# Use Alpine Linux as the base image
FROM alpine:3.21

# Install dependencies and Elm
RUN apk add --no-cache curl nodejs npm bash build-base

# Install Elm and elm-test globally
RUN npm install -g elm@0.19.1 elm-test

# Create a non-root user 'elmuser'
RUN adduser -D elmuser

# Create /app directory and set ownership
RUN mkdir /app && chown elmuser:elmuser /app

# Set the working directory
WORKDIR /app

# Switch to non-root user BEFORE installing project dependencies
USER elmuser

# Install prebuilt Tailwind CSS from jsDelivr for prototyping
RUN mkdir -p /app/static/css && \
    curl -o /app/static/css/tailwind.min.css https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css

# Set up .bashrc for the non-root user to show Elm and elm-test versions on login
RUN echo 'echo "Elm version: $(elm --version)"' >> /home/elmuser/.bashrc \
  && echo 'echo "elm-test version: $(elm-test --version)"' >> /home/elmuser/.bashrc \
  && echo 'export PATH="$HOME/node_modules/.bin:$PATH"' >> /home/elmuser/.bashrc

# Set the default shell (bash) for the user
CMD ["/bin/bash"]
