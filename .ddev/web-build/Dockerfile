ARG BASE_IMAGE
FROM $BASE_IMAGE

RUN DEBIAN_FRONTEND=noninteractive apt-get install \
    -y -o Dpkg::Options::="--force-confold" --no-install-recommends --no-install-suggests \
    parallel \
    jpegoptim \
    optipng \
    pngcrush \
    gifsicle \
    pngquant \
    libjpeg-turbo-progs \
    libperl6-slurp-perl \
    libfile-slurp-perl

RUN DEBIAN_FRONTEND=noninteractive apt-get install \
    -y -o Dpkg::Options::="--force-confold" --no-install-recommends --no-install-suggests \
    build-essential cmake libtool libwebp-dev unzip autoconf automake m4 nasm pkg-config \
    libpng-dev libmagickcore-dev libmagickwand-dev pngnq libtool unzip

RUN cd ~ && \
    wget https://github.com/mozilla/mozjpeg/archive/v3.3.1.tar.gz && \
    tar xf v3.3.1.tar.gz && \
    cd mozjpeg-3.3.1 && \
    autoreconf -fiv && \
    mkdir build && \
    cd build && \
    sh ../configure --disable-shared --enable-static --prefix=/usr/local && \
    sudo make install && \
    sudo ln -s /usr/local/bin/cjpeg /usr/bin/mozjpeg-cjpeg && \
    sudo ln -s /usr/local/bin/jpegtran /usr/bin/mozjpeg-jpegtran

RUN a2enmod macro
