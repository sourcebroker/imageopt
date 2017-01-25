tx_imageopt {

    directories =

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

    default {
        kraken {
            apikey =
            apipass =
            enabled = 0
            notificationLimitEmail < tx_imageopt.notificationLimitEmail
            disableNotificationLimit < tx_imageopt.disableNotificationLimit
            options {
                lossy = true
            }
        }
        tinypng {
            apikey =
            apipass =
            enabled = 0
            notificationLimitEmail < tx_imageopt.notificationLimitEmail
            disableNotificationLimit < tx_imageopt.disableNotificationLimit
        }
        imageoptim {
            apikey =
            apipass =
            enabled = 0
            notificationLimitEmail < tx_imageopt.notificationLimitEmail
            disableNotificationLimit < tx_imageopt.disableNotificationLimit
            options {
                lossy = true
            }
        }
    }

    providers {

        jpg {
            kraken < tx_imageopt.default.kraken

            tinypng < tx_imageopt.default.tinypng

            imageoptim < tx_imageopt.default.imageoptim

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
            kraken < tx_imageopt.default.kraken

            tinypng < tx_imageopt.default.tinypng

            imageoptim < tx_imageopt.default.imageoptim

            gifsicle {
                command = {executable} --batch --optimize=3 {tempFile}
                enabled = 1
            }
        }

        png {
            kraken < tx_imageopt.default.kraken

            tinypng < tx_imageopt.default.tinypng

            imageoptim < tx_imageopt.default.imageoptim

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