FROM nginx:1.29.4-alpine

WORKDIR /var/www

RUN set -x \
    && addgroup -g 82 -S www-data \
    && adduser -u 82 -D -S -G www-data www-data

RUN sed -i 's/user  nginx;/user  www-data;/g' /etc/nginx/nginx.conf

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
