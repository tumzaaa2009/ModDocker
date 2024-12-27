 
FROM ubuntu:22.04

 

 
RUN apt-get update && apt-get upgrade -y \
    && apt-get install -y \
    software-properties-common \
    gcc \
    make \
    build-essential \
    autoconf \
    automake \
    libtool \
    libcurl4-openssl-dev \
    liblua5.3-dev \
    libfuzzy-dev \
    ssdeep \
    gettext \
    pkg-config \
    libgeoip-dev \
    libyajl-dev \
    doxygen \
    libpcre++-dev \
    libpcre2-16-0 \
    libpcre2-dev \
    libpcre2-posix3 \
    zlib1g \
    zlib1g-dev \
    curl \
    git \
    wget \
    && apt-get clean

 
 
# ติดตั้ง nginx
RUN  add-apt-repository ppa:ondrej/nginx -y
 RUN apt-get update && apt-get install -y nginx
COPY ./html/index.html /usr/share/nginx/html/index.html
COPY ./default.conf /etc/nginx/conf.d/default.conf

# ติดตั้ง ModSecurity
RUN git clone https://github.com/owasp-modsecurity/ModSecurity.git /opt/ModSecurity \
    && chmod -R 777 /opt/ModSecurity \
    && cd /opt/ModSecurity \
    && git submodule init \
    && git submodule update \
    && ./build.sh \
    && ./configure \
    && make \
    && make install \
    && cp /opt/ModSecurity/modsecurity.conf-recommended /etc/nginx/modsecurity.conf \
    && cp /opt/ModSecurity/unicode.mapping /etc/nginx/unicode.mapping

# # ติดตั้ง ModSecurity-nginx
RUN git clone https://github.com/owasp-modsecurity/ModSecurity-nginx.git /opt/ModSecurity-nginx 
RUN wget https://nginx.org/download/nginx-1.26.2.tar.gz -O /opt/nginx-1.26.2.tar.gz \
    && cd /opt \
    && tar -xzvf nginx-1.26.2.tar.gz \
    && chmod -R 777 /opt/nginx-1.26.2  \
    && cd nginx-1.26.2 \
    && ./configure --with-compat --add-dynamic-module=/opt/ModSecurity-nginx \
    && make \
    && make modules \
    && cp objs/ngx_http_modsecurity_module.so /etc/nginx/modules-enabled/

 
RUN chmod -R 777 /opt/ModSecurity-nginx   

COPY ./conf/default /etc/nginx/sites-enabled/default
COPY ./conf/nginx.conf /etc/nginx/nginx.conf
COPY ./conf/modsecurity.conf /etc/nginx/modsecurity.conf
 
RUN git clone https://github.com/coreruleset/coreruleset.git /opt/owasp-crs \
    && cp -r /opt/owasp-crs /etc/nginx/ \
    && mv /etc/nginx/owasp-crs/crs-setup.conf.example /etc/nginx/owasp-crs/crs-setup.conf


    

# เปิดพอร์ต 80
EXPOSE 80

# คำสั่งเริ่มต้นเมื่อ container รัน
CMD ["nginx", "-g", "daemon off;"]
 