<?php
namespace MobileDetectTests\UnitTests;

use MobileDetect\Device\DeviceType;

class DetectingTest extends \PHPUnit_Framework_TestCase
{
    /**
     * If the User-Agent is found in the phones DB then deviceType is MOBILE.
     */
    public function testIfTheUserAgentIsFoundInThePhonesDBThenDeviceTypeIsMOBILE()
    {
        /**
         * @var $mobileDetect \MobileDetect\MobileDetect|\PHPUnit_Framework_MockObject_MockObject
         */
        $mobileDetect = $this->getMockBuilder('\MobileDetect\MobileDetect')
                        ->setConstructorArgs(array(
                            'An user agent string'
                        ))
                        ->setMethods(array(
                            'searchForPhoneInDb',
                            'searchForBrowserInDb',
                            'searchForOperatingSystemInDb'
                        ))
                        ->getMock();

        $mobileDetect->expects($this->any())
                        ->method('searchForPhoneInDb')
                        ->willReturn(array(
                            'vendor' => 'a',
                            'identityMatches' => 'b',
                            'modelMatches' => array('c','d')
        ));
        $mobileDetect->expects($this->any())
                        ->method('searchForBrowserInDb')
                        ->willReturn(false);
        $mobileDetect->expects($this->any())
                        ->method('searchForOperatingSystemInDb')
                        ->willReturn(false);

        $props = $mobileDetect->detect();

        $this->assertArrayHasKey('type', $props);
        $this->assertSame($props['type'], DeviceType::MOBILE);
    }

    /**
     * If the User-Agent is found in the tablets DB then deviceType is TABLET.
     */
    public function testIfTheUserAgentIsFoundInTheTabletsDBThenDeviceTypeIsTABLET()
    {
        /**
         * @var $mobileDetect \MobileDetect\MobileDetect|\PHPUnit_Framework_MockObject_MockObject
         */
        $mobileDetect = $this->getMockBuilder('\MobileDetect\MobileDetect')
            ->setConstructorArgs(array(
                'An user agent string'
            ))
            ->setMethods(array(
                'searchForTabletInDb',
                'searchForBrowserInDb',
                'searchForOperatingSystemInDb'
            ))
            ->getMock();

        $mobileDetect->expects($this->any())
            ->method('searchForTabletInDb')
            ->willReturn(array(
                'vendor' => 'a',
                'identityMatches' => 'b',
                'modelMatches' => array('c','d')
            ));
        $mobileDetect->expects($this->any())
            ->method('searchForBrowserInDb')
            ->willReturn(false);
        $mobileDetect->expects($this->any())
            ->method('searchForOperatingSystemInDb')
            ->willReturn(false);

        $props = $mobileDetect->detect();

        $this->assertArrayHasKey('type', $props);
        $this->assertSame($props['type'], DeviceType::TABLET);
    }

    /**
     * If the User-Agent is not found in the phones and tablets DB but it matches only the mobile browser then deviceType is MOBILE.
     */
    public function testIfTheUserAgentIsNotFoundInThePhonesAndTabletsDBButItMatchesOnlyTheMobileBrowserThenDeviceTypeIsMOBILE()
    {
        /**
         * @var $mobileDetect \MobileDetect\MobileDetect|\PHPUnit_Framework_MockObject_MockObject
         */
        $mobileDetect = $this->getMockBuilder('\MobileDetect\MobileDetect')
            ->setConstructorArgs(array(
                'An user agent string'
            ))
            ->setMethods(array(
                'searchForPhoneInDb',
                'searchForTabletInDb',
                'searchForBrowserInDb',
                'searchForOperatingSystemInDb'
            ))
            ->getMock();

        $mobileDetect->expects($this->any())
            ->method('searchForPhoneInDb')
            ->willReturn(false);

        $mobileDetect->expects($this->any())
            ->method('searchForTabletInDb')
            ->willReturn(false);

        $mobileDetect->expects($this->any())
            ->method('searchForBrowserInDb')
            ->willReturn(array(
                'vendor' => 'a',
                'isMobile' => true,
                'identityMatches' => 'b',
                'versionMatches' => array('c', 'd'),
            ));

        $mobileDetect->expects($this->any())
            ->method('searchForOperatingSystemInDb')
            ->willReturn(false);

        $props = $mobileDetect->detect();

        $this->assertArrayHasKey('type', $props);
        $this->assertSame($props['type'], DeviceType::MOBILE);
    }

    /**
     * If the User-Agent is not found in the phones and tablets DB but it matches only the mobile operating system then deviceType is MOBILE.
     */
    public function testIfTheUserAgentIsNotFoundInThePhonesAndTabletsDBButItMatchesOnlyTheMobileOperatingSystemThenDeviceTypeIsMOBILE()
    {
        /**
         * @var $mobileDetect \MobileDetect\MobileDetect|\PHPUnit_Framework_MockObject_MockObject
         */
        $mobileDetect = $this->getMockBuilder('\MobileDetect\MobileDetect')
            ->setConstructorArgs(array(
                'An user agent string'
            ))
            ->setMethods(array(
                'searchForPhoneInDb',
                'searchForTabletInDb',
                'searchForBrowserInDb',
                'searchForOperatingSystemInDb'
            ))
            ->getMock();

        $mobileDetect->expects($this->any())
            ->method('searchForPhoneInDb')
            ->willReturn(false);

        $mobileDetect->expects($this->any())
            ->method('searchForTabletInDb')
            ->willReturn(false);

        $mobileDetect->expects($this->any())
            ->method('searchForBrowserInDb')
            ->willReturn(false);

        $mobileDetect->expects($this->any())
            ->method('searchForOperatingSystemInDb')
            ->willReturn(array(
                'vendor' => 'a',
                'isMobile' => true,
                'identityMatches' => 'b',
                'versionMatches' => array('c', 'd'),
            ));

        $props = $mobileDetect->detect();

        $this->assertArrayHasKey('type', $props);
        $this->assertSame($props['type'], DeviceType::MOBILE);
    }

    /**
     * If the User-Agent is not found in the phones and tablets database or mobile browsers or mobile operating systems then deviceType is DESKTOP.
     */
    public function testIfTheUserAgentIsNotFoundInThePhonesAndTabletsDatabaseOrMobileBrowsersOrMobileOperatingSystemsThenDeviceTypeIsDESKTOP()
    {
        /**
         * @var $mobileDetect \MobileDetect\MobileDetect|\PHPUnit_Framework_MockObject_MockObject
         */
        $mobileDetect = $this->getMockBuilder('\MobileDetect\MobileDetect')
            ->setConstructorArgs(array(
                'An user agent string'
            ))
            ->setMethods(array(
                'searchForPhoneInDb',
                'searchForTabletInDb',
                'searchForBrowserInDb',
                'searchForOperatingSystemInDb'
            ))
            ->getMock();

        $mobileDetect->expects($this->any())
            ->method('searchForPhoneInDb')
            ->willReturn(false);

        $mobileDetect->expects($this->any())
            ->method('searchForTabletInDb')
            ->willReturn(false);

        $mobileDetect->expects($this->any())
            ->method('searchForBrowserInDb')
            ->willReturn(false);

        $mobileDetect->expects($this->any())
            ->method('searchForOperatingSystemInDb')
            ->willReturn(false);

        $props = $mobileDetect->detect();

        $this->assertArrayHasKey('type', $props);
        $this->assertSame($props['type'], DeviceType::DESKTOP);
    }
}