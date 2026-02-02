FROM nginx:1.29.4-alpine

WORKDIR /var/www

COPY --from=shopee-app /var/www /var/www

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
