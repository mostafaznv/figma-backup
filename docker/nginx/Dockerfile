FROM nginx:alpine

RUN ln -sf /dev/stdout /var/log/nginx/access.log && \
    ln -sf /dev/stderr /var/log/nginx/error.log && \
    rm -rf /etc/nginx/conf.d/*

# install openssl
RUN apk add --update openssl && \
    rm -rf /var/cache/apk/*


# create folder for certificates
RUN mkdir -p /etc/nginx/certificates


# put nginx config
COPY ./nginx.conf /etc/nginx/conf.d/default.conf
