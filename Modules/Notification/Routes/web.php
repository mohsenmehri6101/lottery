<?php
/*

routes :
/my-new-notifications
 action : what we do ?
    1 - get all role's from user
        -  get all notification define from roles_user (notifications id)
    2 - get all permissions from user
        -  get all notification define from permissions_user (notifications id)
    3 - get notifications from user
        - (notifications id)
    sum all notifications_id and unique and sort them and check send_at all of them.

/my-notifications
in notification_user get all notifications sent from user and with relationship notification_sent table

*** notice:
*** *** when define user should be created in table channel_event_user from every channels record(for the first time we can use firstOrCreate)
*** ***

notifications
    title,
    text,
    send_at
	user_creator
	user_editor
    send_at
    created_at
    updated_at
    deleted_at
	//morphs
-------------------------
notification_sent_user
    notification_id
    user_id
    read_at

notification_user
    notification_id
    user_id
--------------------------
notification_permission
    notification_id
    permission_id
--------------------------
notification_role
    notification_id
    role_id
--------------------------
notification_template
        columns: title,text,channel_id
--------------------------
notification_user
----------------------------------------------------------------------------------
channels
	name(unique) define in project const
	description

for example channels : telegram , email , sms , notification_application
---------------------
events
	name(unique) define in project const
    title
	description
    notification_template_id
for example : login , register , new_post , factor_done , profile_update
---------------------
channel_event_user
	channel_id
	event_id
	user_id

what user enable/disable channel from notify-event
---------------------

need helper function notification_fire(event fire) create notifications from user(users have permission)(users have roles)

 *
 */
