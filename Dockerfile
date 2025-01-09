 
FROM ubuntu:22.04

 

 
RUN apt-get update && apt-get upgrade -y \
    && apt-get install -y geoipupdate -y \ 
    && apt-get install -y tzdata \
    && apt-get install -y \
    && apt-get install -y cron \
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

RUN echo "Asia/Bangkok" > /etc/timezone && \
    dpkg-reconfigure -f noninteractive tzdata
 
# ติดตั้ง nginx
RUN  add-apt-repository ppa:ondrej/nginx -y
RUN apt-get update && apt-get install -y nginx
 
COPY ./default.conf /etc/nginx/conf.d/default.conf

# ติดตั้ง ModSecurity
RUN git clone https://github.com/owasp-modsecurity/ModSecurity.git /opt/ModSecurity \
    && chmod -R 755 /opt/ModSecurity \
    && cd /opt/ModSecurity \
    && git submodule init \
    && git submodule update \
    && ./build.sh \
    && ./configure \
    && make \
    && make install \
    && cp /opt/ModSecurity/modsecurity.conf-recommended /etc/nginx/modsecurity.conf \
    && cp /opt/ModSecurity/unicode.mapping /etc/nginx/unicode.mapping

# ติดตั้ง ModSecurity-nginx
RUN git clone https://github.com/owasp-modsecurity/ModSecurity-nginx.git /opt/ModSecurity-nginx 
RUN wget https://nginx.org/download/nginx-1.26.2.tar.gz -O /opt/nginx-1.26.2.tar.gz \
    && cd /opt \
    && tar -xzvf nginx-1.26.2.tar.gz \
    && chmod -R 755 /opt/nginx-1.26.2  \
    && cd nginx-1.26.2 \
    && ./configure --with-compat --add-dynamic-module=/opt/ModSecurity-nginx \
    && make \
    && make modules \
    && cp objs/ngx_http_modsecurity_module.so /etc/nginx/modules-enabled/

 
RUN chmod -R 755 /opt/ModSecurity-nginx   

COPY ./conf/default /etc/nginx/sites-enabled/default
COPY ./conf/nginx.conf /etc/nginx/nginx.conf
COPY ./conf/modsecurity.conf /etc/nginx/modsecurity.conf

COPY ./ssl/bundle.crt /etc/ssl/bundle.crt
COPY ./ssl/wildcard_moph_go_th.key /etc/ssl/wildcard_moph_go_th.key
 

RUN git clone https://github.com/coreruleset/coreruleset.git /opt/owasp-crs \
    && cp -r /opt/owasp-crs /etc/nginx/ \
    && mv /etc/nginx/owasp-crs/crs-setup.conf.example /etc/nginx/owasp-crs/crs-setup.conf

COPY ./conf/crs-setup.custom.conf /etc/nginx/owasp-crs/rules/crs-setup.custom.conf 

 
# BlockIp 
COPY ./iplistblock/blocklistip.text /etc/modsecurity/blacklist.txt


#CrontabRm
COPY ./shtruncatelog/truncate.sh /usr/local/bin/truncate.sh
RUN chmod +x /usr/local/bin/truncate.sh

 


EXPOSE 443

# คำสั่งเริ่มต้นเมื่อ container รัน
CMD ["/bin/bash", "-c", "/usr/local/bin/truncate.sh & nginx -g 'daemon off;'"]