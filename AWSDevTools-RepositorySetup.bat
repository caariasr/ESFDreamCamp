@echo on
 powershell -noprofile -executionpolicy bypass -Command "& { Import-Module AWSDevTools; Initialize-AWSElasticBeanstalkRepository }"
pause
