# Build like: docker build -t converter .
# Use like: docker run --rm -t -v $(pwd):/project -w /project converter

FROM hyperized/prestissimo:latest as dependencies
# Quickly install project dependencies with prestissimo
WORKDIR /project
COPY ./ /project/
RUN composer install

FROM hyperized/phive:latest as builder
# Package project with phive + humbug/box
WORKDIR /project
COPY --from=dependencies /project /project
COPY --from=hyperized/prestissimo:latest /usr/bin/composer /usr/bin/composer
RUN phive install humbug/box --force-accept-unsigned
RUN /project/tools/box compile

FROM php:7.4-cli-alpine
COPY --from=builder /project/converter.phar /usr/bin/converter
ENTRYPOINT ["/usr/bin/converter"]
CMD ["-h"]
