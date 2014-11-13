//
//  API.h
//  librecon
//
//  Created by Sergio Garcia on 16/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "AFNetworking.h"

#define SCHEDULES @"schedules"
#define ASSISTANTS @"assistants"
#define TXOKOS @"txokos"
#define STANDS @"expositors"
#define MEETINGS @"meetings"
#define SPONSORS @"sponsors"
#define PHOTOCALL @"photocall"


@interface API : NSObject

typedef void (^APISuccess)(NSDictionary *content);
typedef void (^APIFailure)(AFHTTPRequestOperation *operation, NSError *error);
typedef void (^APIMeetingDuplicated)(NSInteger statusCode);

+ (API *)sharedClient;
+ (void)setCustomHeader;

//Login control
- (void)forceUserLogin;

//Authentication
- (void)athenticateWithCode:(NSString *)code withOnSuccessHandler:(APISuccess)successHandler andFailureHandler:(APIFailure)failureHandler;
- (void)requestCodeWithEmail:(NSString *)email withOnSuccessHandler:(APISuccess)successHandler andFailureHandler:(APIFailure)failureHandler;
- (void)sendUUID:(NSString *)uuid;

//Data
- (void)getSchedulesWithOnSuccessHandler:(APISuccess)successHandler andFailureHandler:(APIFailure)failureHandler;
- (void)getAssistentsWithOnSuccessHandler:(APISuccess)successHandler andFailureHandler:(APIFailure)failureHandler;
- (void)getTxokosWithOnSuccessHandler:(APISuccess)successHandler andFailureHandler:(APIFailure)failureHandler;
- (void)getStandsWithOnSuccessHandler:(APISuccess)successHandler andFailureHandler:(APIFailure)failureHandler;
- (void)getMeetingsWithOnSuccessHandler:(APISuccess)successHandler andFailureHandler:(APIFailure)failureHandler;
- (void)getMeetingWithId:(NSString *)idMeeting WithOnSuccessHandler:(APISuccess)successHandler andFailureHandler:(APIFailure)failureHandler;
- (void)createMeetingToAssistant:(NSString *)idAssistant withOnSuccessHandler:(APISuccess)successHandler andDuplicateHandler:(APIMeetingDuplicated)duplicateHandler andFailureHandler:(APIFailure)failureHandler;
- (void)setMeeting:(NSString *)meetingId withMoment:(NSString *)moment andEmailShare:(BOOL)emailShare andPhoneShare:(BOOL)phoneShare withOnSuccessHandler:(APISuccess)successHandler andFailureHandler:(APIFailure)failureHandler;
- (void)getSponsorsWithOnSuccessHandler:(APISuccess)successHandler andFailureHandler:(APIFailure)failureHandler;
- (void)getPhotosWithOnSuccessHandler:(APISuccess)successHandler andFailureHandler:(APIFailure)failureHandler;
@end
