//
//  GlobalValues.h
//  librecon
//
//  Created by Sergio Garcia on 15/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import <Foundation/Foundation.h>

#define kAppApiURL @"https://mobile.librecon.io/secret/api/v1/"
#define kAppAboutURL @"https://mobile.librecon.io/about/?lang="

#define kAppProdCertName @"librecon_prod"

//Parallax Effect Value
#define parallaxValue 50

// DATABASE
#define DATABASE_NAME @"librecon3.sqlite"
#define DATAMODEL_NAME @"DataModel"

//IDENTIFIERS
#define IDEN_SCHEDULE @"Schedule"
#define IDEN_ASSISTANT @"Assistant"
#define IDEN_TXOKO @"Txoko"
#define IDEN_STAND @"Stand"
#define IDEN_MEETING @"Meeting"
#define IDEN_SPONSOR @"Sponsor"

//UPDATE NOTIFICATIONS
#define NOTIFI_SCHEDULES_UPDATED @"updatedSchedules"
#define NOTIFI_ASSISTANTS_UPDATED @"updatedAssistants"
#define NOTIFI_TXOKOS_UPDATED @"updatedTxokos"
#define NOTIFI_STAND_UPDATED @"updatedStands"
#define NOTIFI_MEETING_UPDATED @"updatedMeetings"
#define NOTIFI_SPONSOR_UPDATED @"updatedSponsors"

//NOTIFICATIONS
#define NOTIFICATION_MEETING_FROM_PUSH @"openMeetingNotification"

//CACHES
#define CACHE_SCHEDULE_DAY11 @"ScheduleDayOne"
#define CACHE_SCHEDULE_DAY12 @"ScheduleDayTwo"
#define CACHE_ASSISTANTS @"Assistant"
#define CACHE_TXOKOS @"Txoko"
#define CACHE_STAND @"Stand"
#define CACHE_MEETING @"Meeting"
#define CACHE_SPONSOR @"Sponsor"

@interface GlobalValues : NSObject

@end
