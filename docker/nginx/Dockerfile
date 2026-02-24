FROM node:22.16-alpine AS build-stage
LABEL authors="ra1zen"
WORKDIR /app

COPY ./frontend/vue/package*.json ./
RUN npm install

COPY ./frontend/vue .
RUN npm run build-only

FROM nginx:alpine
RUN rm -rf /usr/share/nginx/html/*

COPY --from=build-stage /app/dist /var/www/frontend/dist

COPY ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY ./docker/nginx/admin.conf /etc/nginx/conf.d/admin.conf

EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]