# TYPO3 Extension ``imageopt``

This extension allows to optimize images resized by TYPO3 so they will take less space. 
TYPO3 services are used to register the providers of images optimization so own providers 
can be registered also. Cron based. 

Support native linux commands like:

* Png format:
    * optipng 
    * pngcrush
    * pngquant
* Gif format:
    * Gifsicle
* Jpeg format:
    * jpegoptim
    * jpegrescan
    * jpegtran
    * mozjpg

Support for several remotes:
* Kraken (https://kraken.io/)
* Tinypng (https://tinypng.com/)
* Imageoptim (https://imageoptim.com/api)


### Installation

Install the extension using composer ``composer require sourcebroker/imageopt``.

### Configuration

Default configuration is read automaticly:

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


As you see above, by default, only linux native commands are enabled.

If you would like to run only Kraken.io then put this config in you PageTS.

    tx_imageopt {
    
        adminEmail = test@gmail.com
        notificationLimitEmail = test@gmail.com
    
        default {
            kraken {
                apikey = 9d41f741e4df359db85936865e3afaaa
                apipass = df666c5ea90490b0f1ce2b473bdb6ba9bd1f903f
                enabled = 1
                notificationLimitEmail < tx_imageopt.notificationLimitEmail
                disableNotificationLimit < tx_imageopt.disableNotificationLimit
            }
        }
    
        providers {
            jpg >
            jpg {
                kraken < tx_imageopt.default.kraken
            }
            gif >
            gif {
                kraken < tx_imageopt.default.kraken
            }
            png >
            png {
                kraken < tx_imageopt.default.kraken
            }
        }
    }


### Technical note

* The original images, for example in folder fileadmin/, uploads/ are not optmized. Only already resized 
  images are optmized, so for FAL that would be files form "\_processed\_/" folder of file storage.
* Be aware that there are two xclasses to make TYPO3 to process images even if there is no need (because
  for example the requested image size is the same as original). Thanks to that xclasses the original images 
  do not have to be optimized.
* For FAL files only file that are in "sys_file_processedfile" are optimized. Table "sys_file_processedfile" has
  been extended with "tx_imageopt_optimized" field. If file has been optimised then the field "tx_imageopt_optimized"
  is set to 1.
  You can reset the "tx_imageopt_optimized" flag with command
  ``php ./typo3/cli_dispatch.phpsh extbase imageopt:resetoptimizationflag``
* If you enable more than one image optimizer than all are run and the best optimized image choosed.  
* There is table "tx_imageopt_images" where the statistics and winning image manipulation optmimizer is stored.  

### Usage

1) Direct cli call:
   `` php ./typo3/cli_dispatch.phpsh extbase imageopt:optimizeimages``
2) Or better create extbase scheduler job in TYPO3 backend to run task regularly.
    