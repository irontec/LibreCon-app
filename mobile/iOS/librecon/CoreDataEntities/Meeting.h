//
//  Meeting.h
//  librecon
//
//  Created by Sergio Garcia on 15/10/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreData/CoreData.h>

@class MeetingAssistant;

@interface Meeting : NSManagedObject

@property (nonatomic) BOOL cellphoneShare;
@property (nonatomic, retain) NSDate * createdAt;
@property (nonatomic, retain) NSNumber * customOrder;
@property (nonatomic) BOOL emailShare;
@property (nonatomic, retain) NSString * idMeeting;
@property (nonatomic, retain) NSString * moment;
@property (nonatomic, retain) NSDate * responseDate;
@property (nonatomic) BOOL sendedByMe;
@property (nonatomic, retain) NSString * status;
@property (nonatomic, retain) MeetingAssistant *assistant;

@end
