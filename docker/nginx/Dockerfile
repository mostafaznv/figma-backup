FROM nginx:alpine

ARG HOST_UID=1000
ARG HOST_GID=1000
ENV USERNAME=www-data


RUN ln -sf /dev/stdout /var/log/nginx/access.log && \
    ln -sf /dev/stderr /var/log/nginx/error.log && \
    rm -rf /etc/nginx/conf.d/*


# fix permissions
RUN echo http://dl-2.alpinelinux.org/alpine/edge/community/ >> /etc/apk/repositories
RUN apk --no-cache add shadow \
    && usermod -u $HOST_UID -o nginx \
    && groupmod -g $HOST_GID -o nginx



# install openssl
RUN apk add --update openssl && \
    rm -rf /var/cache/apk/*


# create folder for certificates
RUN mkdir -p /etc/nginx/certificates


# put nginx config
COPY ./nginx.conf /etc/nginx/conf.d/default.conf
