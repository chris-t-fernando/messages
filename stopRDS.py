import botocore.session
import boto3

# pull RDS details from SSM parameter store
ssm = boto3.client('ssm')
rdsHost = ssm.get_parameter(Name='/messages/rds-instance-host', WithDecryption=False).get("Parameter").get("Value")
rdsInstance = ssm.get_parameter(Name='/messages/rds-instance-name', WithDecryption=False).get("Parameter").get("Value")
rdsUsername = ssm.get_parameter(Name='/messages/rds-instance-user', WithDecryption=False).get("Parameter").get("Value")
rdsPassword = ssm.get_parameter(Name='/messages/rds-instance-pass', WithDecryption=True).get("Parameter").get("Value")

# start botocore session
session = botocore.session.get_session()
rdsClient = session.create_client('rds', region_name='us-west-2')	

try:
	response = rdsClient.stop_db_instance(
		DBInstanceIdentifier=rdsInstance
	)
	print "Stop command accepted by API"
	
except Exception, e:
	# can't turn it off, already being turned off maybe?
	print "Exception shutting down - " + str(e)
