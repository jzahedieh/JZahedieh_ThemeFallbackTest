# EcomDev_PHPUnit Symlink Controller Issue

I noticed an issue when calling `$this->assertLayoutBlockRendered('content');` 
where the template has been rewritten in a new template which has been symlinked.

This can be resolved by using the fixture:

    config:
      default/dev/template/allow_symlink: 1
      
But as this is a global configuration option there is no way to specify this over a whole test suite
 which causes discrepancies between development and near production environments.


## Composer File

Here is my composer file that brings in this module as well as the EcomDev_PHPUnit module:

    {
        "require": {
            "magento/core": "1.9.0.1",
            "magento-hackathon/magento-composer-installer": "*",
            "jzahedieh/theme-fallback-test": "dev-master"
        },
        "require-dev": {
            "ecomdev/ecomdev_phpunit": "v0.3.4",
            "mikey179/vfsStream": ">=1.2.0"
        },
        "repositories": [
            {
                "type": "composer",
                "url": "http://packages.firegento.com"
            },
            {
                "type": "vcs",
                "url": "git@github.com:jzahedieh/JZahedieh_ThemeFallbackTest.git"
            }
        ],
        "extra": {
            "magento-root-dir": "htdocs/"
        }
    }
    
This will allow you to run the tests from the `htdocs/` folder using `vendor/bin/phpunit  --group JZahedieh_ThemeFallbackTest`

One should fail due to this issue in the following way:

    magento@ubuntu:/var/www/community/htdocs$ /var/www/community/vendor/bin/phpunit  --group JZahedieh_ThemeFallbackTest
    PHPUnit 4.1.6 by Sebastian Bergmann.
    
    Configuration read from /var/www/community/htdocs/phpunit.xml.dist
    
    .F.
    
    Time: 2.07 seconds, Memory: 35.50Mb
    
    There was 1 failure:
    
    1) JZahedieh_ThemeFallbackTest_Test_Controller_Symlink::testDispatchUnittestPackageWithoutSymlinkConfig
    Failed asserting that layout block "content" is rendered.
    
    /var/www/community/vendor/ecomdev/ecomdev_phpunit/lib/EcomDev/PHPUnit/AbstractConstraint.php:247
    /var/www/community/vendor/ecomdev/ecomdev_phpunit/lib/EcomDev/PHPUnit/AbstractConstraint.php:226
    /var/www/community/vendor/ecomdev/ecomdev_phpunit/app/code/community/EcomDev/PHPUnit/Test/Case/Controller.php:948
    /var/www/community/vendor/ecomdev/ecomdev_phpunit/app/code/community/EcomDev/PHPUnit/Test/Case/Controller.php:1129
    /var/www/community/vendor/jzahedieh/theme-fallback-test/app/code/local/JZahedieh/ThemeFallbackTest/Test/Controller/Symlink.php:75
    /var/www/community/vendor/jzahedieh/theme-fallback-test/app/code/local/JZahedieh/ThemeFallbackTest/Test/Controller/Symlink.php:46
    /var/www/community/vendor/phpunit/phpunit/phpunit:55
                                          
    FAILURES!                             
    Tests: 3, Assertions: 30, Failures: 1.


## Potential Fixes

* Add node in `phpunit.xml` to allow for symlinks.
* A global configuration fixture for all test cases. 

## Note

Originally I though this was a theme fall back issue which is why the module is named as is.
