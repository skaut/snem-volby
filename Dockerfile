FROM fmasa/lebeda:7.4
RUN apt-get update && apt-get install -y gnupg2 && \
    curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - && \
    echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list && \
    apt update && apt install -y yarn

COPY www /var/www
COPY . /var/www/html
RUN composer install --no-dev -o
RUN yarn install && yarn build
RUN chmod 777 /var/www/html/log && \
    rm -rf temp/* && mkdir temp/sessions && chmod 777 temp/sessions && \
    rm -rf log/* && chmod 777 log

