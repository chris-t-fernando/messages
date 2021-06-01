import botocore.session
import boto3

# pull RDS details from SSM parameter store
ssm = boto3.client('ssm')
rdsHost = ssm.get_parameter(Name='/messages/rds-instance-host', WithDecryption=False).get("Parameter").get("Value")
rdsInstance = ssm.get_parameter(Name='/messages/rds-instance-name', WithDecryption=False).get("Parameter").get("Value")
rdsUsername = ssm.get_parameter(Name='/messages/rds-instance-user', WithDecryption=False).get("Parameter").get("Value")
rdsPassword = ssm.get_parameter(Name='/messages/rds-instance-pass', WithDecryption=True).get("Parameter").get("Value")

# set up RDS objects
session = botocore.session.get_session()
rdsClient = session.create_client('rds', region_name='us-west-2')

try:
	response = rdsClient.start_db_instance(
		DBInstanceIdentifier=rdsInstance
	)
	print "Start command accepted by API"
	
except Exception, e:
	if str(e).find("is not stopped, cannot be started.") > 0 :
		# its not already powered on
		print "RDS instance already started. " + str(e)
	else:
		# other exceptions
		print "RDS instance could not be started. " + str(e)
		exit()


except Exception, e:
	# set up mysql
	try:
		db = MySQLdb.connect(rdsHost, rdsUsername, rdsPassword, rdsInstance, connect_timeout=5)
	except:
		print "Can't connect to MySQL - quitting. " + str(e)
		exit()
