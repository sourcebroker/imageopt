# TYPO3 Extension ``imageopt``

This extension allows to optimize images resized by TYPO3 so they will take less space. 

Features:

* If you enable more than one image optimizer then all of them will be executed and the best optimized image is choosen.
* Own providers can be registered with TSconfig. 
* Providers can be mixed to create new providers. 

Support native linux commands like:

* for png:
    * optipng 
    * pngcrush
    * pngquant
* for gif:
    * gifsicle
* for jpeg:
    * jpegoptim
    * jpegrescan
    * jpegtran
    * mozjpg


### Installation

Install the extension using composer ``composer require sourcebroker/imageopt``

### Configuration

Default configuration is read automaticly:

    tx_imageopt {
        
        directories =
    
        default {
            
            options {
                quality = 85
            }
    
        }
    
        providers {
            jpeg {
                jpegoptim {
                    command = {executable} {tempFile} -o --strip-all {quality}
                    enabled = 1
                    options {
                        quality < tx_imageopt.default.options.quality
                        qualityOptions {
                            5 = --max=5
                            10 = --max=10
                            15 = --max=15
                            20 = --max=20
                            25 = --max=25
                            30 = --max=30
                            35 = --max=35
                            40 = --max=40
                            45 = --max=45
                            50 = --max=50
                            55 = --max=55
                            60 = --max=60
                            65 = --max=65
                            70 = --max=70
                            75 = --max=75
                            80 = --max=80
                            85 = --max=85
                            90 = --max=90
                            95 = --max=95
                            100 = --max=100
                        }
                    }
                }
    
                jpegrescan {
                    command = {executable} -s {tempFile} {tempFile}
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
                gifsicle {
                    command = {executable} --batch {quality} {tempFile}
                    enabled = 1
                    options {
                        quality < tx_imageopt.default.options.quality
                        qualityOptions {
                            5 = --optimize=3
                            10 = --optimize=3
                            15 = --optimize=3
                            20 = --optimize=3
                            25 = --optimize=3
                            30 = --optimize=3
                            35 = --optimize=3
                            40 = --optimize=2
                            45 = --optimize=2
                            50 = --optimize=2
                            55 = --optimize=2
                            60 = --optimize=2
                            65 = --optimize=2
                            70 = --optimize=2
                            75 = --optimize=1
                            80 = --optimize=1
                            85 = --optimize=1
                            90 = --optimize=1
                            95 = --optimize=1
                            100 = --optimize=1
                        }
                    }
                }
            }
    
            png {
                optipng {
                    command = {executable} {tempFile} -strip "all" {quality}
                    enabled = 1
                    options {
                        quality < tx_imageopt.default.options.quality
                        options {
                            5 = -o7
                            10 = -o7
                            15 = -o7
                            20 = -o6
                            25 = -o6
                            30 = -o6
                            35 = -o5
                            40 = -o5
                            45 = -o5
                            50 = -o4
                            55 = -o4
                            60 = -o4
                            65 = -o3
                            70 = -o3
                            75 = -o3
                            80 = -o2
                            85 = -o2
                            90 = -o2
                            95 = -o1
                            100 = -o0
                        }
                    }
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


Note a config part ``tx_imageopt.default.options.quality = 85``. This quality is mapped to 
diffrent quality settings of each provider in options.qualityOptions array. This way you can
set default quality for all providers.


### Technical notes

* The original images, for example in folder fileadmin/, uploads/ are not optmized. Only already resized 
  images are optmized, so for FAL that would be files form "\_processed\_/" folder of file storages.
  
* Be aware that there are two xclasses to make TYPO3 to process images even if there is no need (because
  for example the requested image size is the same as original). Thanks to that xclasses the original images 
  do not have to be optimized.
  
* For FAL files only file that are in "sys_file_processedfile" are optimized. Table "sys_file_processedfile" has
  been extended with "tx_imageopt_optimized" field. If file has been optimised then the field "tx_imageopt_optimized"
  is set to 1.
  
  You can reset the "tx_imageopt_optimized" flag with command
  ``php ./typo3/cli_dispatch.phpsh extbase imageopt:resetoptimizationflag``
  
* There is table "tx_imageopt_images" where the statistics and winning image optmimizer is stored.  

### Usage

1) Direct cli call:
   `` php ./typo3/cli_dispatch.phpsh extbase imageopt:optimizefalprocessedimages``
   
2) Or better create extbase scheduler job in TYPO3 backend to run task regularly.
    