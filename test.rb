# require 'rubygems'
# require 'selenium-webdriver'
#
# # Input capabilities
# caps = Selenium::WebDriver::Remote::Capabilities.new
# caps.setCapability("browserName", "android");
#     caps.setCapability("device", "Samsung Galaxy S7");
#     caps.setCapability("realMobile", "true");
#     caps.setCapability("os_version", "6.0");
#
# driver = Selenium::WebDriver.for(:remote,
#   :url => "http://alexanderhorner3:ACCESS_KEY@hub-cloud.browserstack.com/wd/hub",
#   :desired_capabilities => caps)
# driver.navigate.to "http://www.google.com"
# element = driver.find_element(:name, 'q')
# element.send_keys "BrowserStack"
# element.submit
# puts driver.title
# driver.quit
puts test
