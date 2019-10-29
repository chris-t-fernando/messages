import botocore.session


session = botocore.session.get_session()
rdsClient = session.create_client('rds', region_name='us-west-2')

try:
	response = rdsClient.start_db_instance(
		DBInstanceIdentifier='jtweets'
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
		db = MySQLdb.connect("jtweets.ciizausrav91.us-west-2.rds.amazonaws.com", "jtweets", "jtweets1", "jtweets", connect_timeout=5)
	except:
		print "Can't connect to MySQL - quitting. " + str(e)
		exit()
