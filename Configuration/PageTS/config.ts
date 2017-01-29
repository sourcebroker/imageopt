tx_imageopt {

    directories =

    default {

        limits {
            notification {
                sender {
                    email =
                    name =
                }
                reciver {
                    email =
                    name =
                }
                disable = 0
            }
        }

        providers {
            kraken {
                apikey =
                apipass =
                enabled = 0
                limits < tx_imageopt.default.limits
                options {
                    lossy = true
                }
            }

            tinypng {
                apikey =
                apipass =
                enabled = 0
                limits < tx_imageopt.default.limits
            }

            imageoptim {
                apikey =
                apipass =
                enabled = 0
                limits < tx_imageopt.default.limits
                options {
                    lossy = true
                }
            }
        }
    }

    providers {

        jpg {
            kraken < tx_imageopt.default.providers.kraken

            tinypng < tx_imageopt.default.providers.tinypng

            imageoptim < tx_imageopt.default.providers.imageoptim

            jpegoptim {
                command = {executable} {tempFile} -o
                enabled = 1
            }

            jpegrescan {
                command = {executable} {tempFile} {tempFile}
                enabled = 1
            }

            jpegtran {
                command = {executable} -copy none -optimize -progressive -outfile {tempFile} {tempFile}
                enabled = 1
            }

            mozjpg {
                command = {executable} -copy none {tempFile} > {tempFile}
                enabled = 1
            }
        }

        gif {
            kraken < tx_imageopt.default.providers.kraken

            tinypng < tx_imageopt.default.providers.tinypng

            imageoptim < tx_imageopt.default.providers.imageoptim

            gifsicle {
                command = {executable} --batch --optimize=3 {tempFile}
                enabled = 1
            }
        }

        png {
            kraken < tx_imageopt.default.providers.kraken

            tinypng < tx_imageopt.default.providers.tinypng

            imageoptim < tx_imageopt.default.providers.imageoptim

            optipng {
                command = {executable} {tempFile}
                enabled = 1
            }

            pngcrush {
                command = {executable} -q -rem alla -brute -reduce -ow {tempFile} >/dev/null
                enabled = 1
            }

            pngquant {
                command = {executable} {tempFile} --force --ext ''
                enabled = 1
            }
        }
    }
}