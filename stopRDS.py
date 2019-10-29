import botocore.session


session = botocore.session.get_session()
rdsClient = session.create_client('rds', region_name='us-west-2')	

try:
	response = rdsClient.stop_db_instance(
		DBInstanceIdentifier='jtweets'
	)
	print "Stop command accepted by API"
	
except Exception, e:
	# can't turn it off, already being turned off maybe?
	print "Exception shutting down - " + str(e)
