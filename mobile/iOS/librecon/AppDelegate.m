//
//  AppDelegate.m
//  librecon
//
//  Created by Sergio Garcia on 15/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import "AppDelegate.h"
#import "SWRevealViewController.h"
#import "UIColor+Librecon.h"
#import "API.h"
#import "UserDefaultsHelper.h"
#import "AFNetworkReachabilityManager.h"
#import "SVProgressHUD.h"
#import "MeetingViewController.h"
#import "JSONToObjectMapper.h"
#import "Schedule.h"
#import "ScheduleMapper.h"
#import "Tag.h"
#import "Assistant.h"
#import "Txoko.h"
#import "Stand.h"
#import "Meeting.h"
#import "MeetingMapper.h"
#import "Sponsor.h"

@implementation AppDelegate {
    
    API *_api;
    BOOL isSchedulesLoading, isAssistantsLoading, isMeetingsLoading, isTxokosLoading, isStandsLoading, isSponsorsLoading;
}

@synthesize managedObjectContext = _managedObjectContext;
@synthesize managedObjectModel = _managedObjectModel;
@synthesize persistentStoreCoordinator = _persistentStoreCoordinator;

- (BOOL)application:(UIApplication *)application didFinishLaunchingWithOptions:(NSDictionary *)launchOptions
{
    [[AFNetworkReachabilityManager sharedManager] startMonitoring];
    [UserDefaultsHelper registerAllDefaults];
    isSchedulesLoading = isAssistantsLoading = isMeetingsLoading = isTxokosLoading = isStandsLoading = NO;
    
    NSURLSessionConfiguration *sessionConfiguration = [NSURLSessionConfiguration defaultSessionConfiguration];
    NSURLCache *cache = [[NSURLCache alloc] initWithMemoryCapacity:20 * 1024 * 1024
                                                      diskCapacity:100 * 1024 * 1024
                                                          diskPath:nil];
    sessionConfiguration.URLCache = cache;
    sessionConfiguration.requestCachePolicy = NSURLRequestUseProtocolCachePolicy;
    
    _api = [API sharedClient];
    [self checkDataState];
    [self customizeNavigationBar];
    
    if ([[UIApplication sharedApplication] respondsToSelector:@selector(registerUserNotificationSettings:)]) {
        [[UIApplication sharedApplication] registerUserNotificationSettings:[UIUserNotificationSettings
                                                                             settingsForTypes:(UIUserNotificationTypeSound |
                                                                                               UIUserNotificationTypeAlert |
                                                                                               UIUserNotificationTypeBadge)
                                                                             categories:nil]];
        [[UIApplication sharedApplication] registerForRemoteNotifications];
    }
    else {
        [[UIApplication sharedApplication] registerForRemoteNotificationTypes:(UIUserNotificationTypeBadge |
                                                                               UIUserNotificationTypeSound |
                                                                               UIUserNotificationTypeAlert)];
    }
    
    NSString *userHash = [UserDefaultsHelper getUserHash];
    if ([UserDefaultsHelper getAnonymous] || (userHash && ![userHash isEqualToString:@""])) {
        [self loadMainController];
    }
    
    if (launchOptions != nil) {
        NSDictionary *dictionary = [launchOptions objectForKey:UIApplicationLaunchOptionsRemoteNotificationKey];
        NSString *userHash = [UserDefaultsHelper getUserHash];
        BOOL hasDoLogin = (userHash && ![userHash isEqualToString:@""]) ? YES : NO;
        if (dictionary != nil && hasDoLogin) {
            NSLog(@"Launched from PUSH notification: %@", dictionary);
            //launch main controller with meeting in front
            [self handleNotification:dictionary[@"meetingId"]];
            return YES;
        }
    }
    return YES;
}

- (void)applicationWillResignActive:(UIApplication *)application {}

- (void)applicationDidEnterBackground:(UIApplication *)application {}

- (void)applicationWillEnterForeground:(UIApplication *)application {}

- (void)applicationDidBecomeActive:(UIApplication *)application {}

- (void)applicationWillTerminate:(UIApplication *)application
{
    [self saveContext];
}

#pragma mark PUSH notifications

- (void)application:(UIApplication*)application didRegisterForRemoteNotificationsWithDeviceToken:(NSData*)mDeviceToken
{
    NSString *stringToken = [[mDeviceToken description] stringByTrimmingCharactersInSet:[NSCharacterSet characterSetWithCharactersInString:@"<>"]];
    stringToken = [stringToken stringByReplacingOccurrencesOfString:@" " withString:@""];
    NSLog(@"My notification token is: %@", stringToken);
    [self updateDeviceWithUUID:stringToken];
}

- (void)application:(UIApplication*)application didFailToRegisterForRemoteNotificationsWithError:(NSError*)error
{
    NSLog(@"Failed to get token, error: %@", error);
    [self updateDeviceWithUUID:@""];
}

- (void)updateDeviceWithUUID:(NSString *)uuid
{
    [UserDefaultsHelper setUUID:uuid];
    [_api sendUUID:uuid];
}

- (void)application:(UIApplication*)application didReceiveRemoteNotification:(NSDictionary*)userInfo
{
    NSLog(@"Received notification: %@", userInfo);
    [self checkMeetings];
    if (application.applicationState == UIApplicationStateActive) {
        NSLog(@"ACTIVE");
        NSString *message = userInfo[@"aps"][@"alert"];
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:message
                                                        message:nil
                                                       delegate:self
                                              cancelButtonTitle:NSLocalizedString(@"CERRAR", nil)
                                              otherButtonTitles:NSLocalizedString(@"VER", nil) , nil];
        alert.tag = [userInfo[@"meetingId"] integerValue];
        dispatch_async(dispatch_get_main_queue(), ^{
            [alert show];
        });
    } else {
        NSLog(@"NO ACTIVE");
        [self removeNotificationFromNotificationsCenter];
        [self handleNotification:userInfo[@"meetingId"]];
    }
}

- (void)alertView:(UIAlertView *)alertView clickedButtonAtIndex:(NSInteger)buttonIndex {
    
    if (buttonIndex == 1) {
        [self handleNotification:[NSString stringWithFormat:@"%d", alertView.tag]];
    }
}

- (void)removeNotificationFromNotificationsCenter
{
    [[UIApplication sharedApplication] setApplicationIconBadgeNumber: 1];
    [[UIApplication sharedApplication] setApplicationIconBadgeNumber: 0];
    [[UIApplication sharedApplication] cancelAllLocalNotifications];
}

#pragma mark - Handle Notifications

- (void)handleNotification:(NSString *)meetingId
{
    NSLog(@"Handle notification");
    [_api getMeetingWithId:meetingId WithOnSuccessHandler:^(NSDictionary *content) {
        Meeting *meeting = [NSEntityDescription
                            insertNewObjectForEntityForName:IDEN_MEETING
                            inManagedObjectContext:_managedObjectContext];
        [MeetingMapper deserializeSelectedKeysFrom:content[@"data"][@"meeting"] toObject:meeting fromContext:_managedObjectContext];
        meeting.customOrder = [NSNumber numberWithInteger:other];
        [_managedObjectContext save:nil];
        NSMutableDictionary *data = [[NSMutableDictionary alloc] init];
        data[@"meetingId"] = meeting.idMeeting;
        [[NSNotificationCenter defaultCenter] postNotificationName:NOTIFICATION_MEETING_FROM_PUSH object:nil userInfo:data];
    } andFailureHandler:^(AFHTTPRequestOperation *operation, NSError *error) {
        NSLog(@"Error on handleNotification with id: %@", meetingId);
    }];
}

#pragma mark - Customize Navigation Bar

- (void)customizeNavigationBar {
    
    [[UIApplication sharedApplication] setStatusBarStyle:UIStatusBarStyleLightContent];
    [[UINavigationBar appearance] setBarTintColor:[UIColor navigationBarBackgroundColor]];
    [[UINavigationBar appearance] setTitleTextAttributes: [NSDictionary dictionaryWithObjectsAndKeys:
                                                           [UIColor whiteColor], NSForegroundColorAttributeName,
                                                           nil]];
    [[UINavigationBar appearance] setTintColor:[UIColor whiteColor]];
    
    [[UITabBar appearance] setBarTintColor:[UIColor navigationBarBackgroundColor]];
    
    
    [[UITabBarItem appearance] setTitleTextAttributes:[NSDictionary dictionaryWithObjectsAndKeys:
                                                       [UIColor tabBarSelectedColor], NSForegroundColorAttributeName,
                                                       nil]
                                             forState:UIControlStateSelected];
    
    [[UITabBarItem appearance] setTitleTextAttributes:[NSDictionary dictionaryWithObjectsAndKeys:
                                                       [UIColor tabBarUnselectedColor], NSForegroundColorAttributeName,
                                                       nil]
                                             forState:UIControlStateNormal];
}

#pragma mark - Load Controllers

- (void)loadLoginController
{
    UIStoryboard *sb = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
    UINavigationController *nc = [sb instantiateViewControllerWithIdentifier:@"loginNavigationController"];
    [UIView transitionWithView:self.window
                      duration:0.5
                       options:UIViewAnimationOptionTransitionCrossDissolve
                    animations:^(void) {
                        BOOL oldState = [UIView areAnimationsEnabled];
                        [UIView setAnimationsEnabled:NO];
                        self.window.rootViewController = nc;
                        [UIView setAnimationsEnabled:oldState];
                    }
                    completion:nil];
}

- (void)loadMainController
{
    UIStoryboard *sb = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
    SWRevealViewController *vc = [sb instantiateViewControllerWithIdentifier:@"MainController"];
    [UIView transitionWithView:self.window
                      duration:0.5
                       options:UIViewAnimationOptionTransitionCrossDissolve
                    animations:^(void) {
                        BOOL oldState = [UIView areAnimationsEnabled];
                        [UIView setAnimationsEnabled:NO];
                        self.window.rootViewController = vc;
                        [UIView setAnimationsEnabled:oldState];
                    }
                    completion:nil];
}

#pragma mark - Data Control


- (void)checkDataState {
    
    if ([[UserDefaultsHelper getUserHash] isEqualToString:@""]) {
        NSLog(@"Not authenticated!");
        return;
    }
    [self checkSchedules];
    [self checkAssistants];
    [self checkTxokos];
    [self checkStands];
    [self checkMeetings];
    [self checkSponsors];
}

- (void)checkSchedules {
    
    NSLog(@"Cheking schedules..");
    
    if (isSchedulesLoading) {
        NSLog(@"Schedules allready loading..");
        return;
    }
    isSchedulesLoading = YES;
    NSManagedObjectContext *globalContext = [self managedObjectContext];
    
    NSManagedObjectContext *managedObjectContextForThread = [[NSManagedObjectContext alloc] initWithConcurrencyType:NSPrivateQueueConcurrencyType];
    [managedObjectContextForThread setPersistentStoreCoordinator:globalContext.persistentStoreCoordinator];
    
    [_api getSchedulesWithOnSuccessHandler:^(NSDictionary *content) {
        [self deleteAllEntitiesWithIdentifier:IDEN_SCHEDULE];
        
        NSArray *data = (NSArray *)content[@"data"][@"schedules"];
        NSLog(@"Total schedules: %lu", (unsigned long)data.count);
        
        NSString *version = content[@"data"][@"version"];
        NSLog(@"Version: %@", version);
        for (NSDictionary *scheduleData in data) {
            Schedule *schedule = [NSEntityDescription
                                  insertNewObjectForEntityForName:IDEN_SCHEDULE
                                  inManagedObjectContext:managedObjectContextForThread];
            [ScheduleMapper deserializeSelectedKeysFrom:scheduleData toObject:schedule fromContext:managedObjectContextForThread];
            
            NSError *error;
            if (![managedObjectContextForThread save:&error]) {
                NSLog(@"Error saving schedule: %@", [error localizedDescription]);
            }
            if (![globalContext save:&error]) {
                NSLog(@"Error saving globalContext: %@", [error localizedDescription]);
            }
        }
        [NSFetchedResultsController deleteCacheWithName:CACHE_SCHEDULE_DAY11];
        [NSFetchedResultsController deleteCacheWithName:CACHE_SCHEDULE_DAY12];
        
        NSMutableDictionary *userInfo = [[NSMutableDictionary alloc] init];
        userInfo[@"result"] = [NSNumber numberWithBool:YES];
        [[NSNotificationCenter defaultCenter] postNotificationName:NOTIFI_SCHEDULES_UPDATED object:userInfo];
        isSchedulesLoading = NO;
    } andFailureHandler:^(AFHTTPRequestOperation *operation, NSError *error) {
        //check error and relaunch
        NSMutableDictionary *userInfo = [[NSMutableDictionary alloc] init];
        userInfo[@"result"] = [NSNumber numberWithBool:NO];
        [[NSNotificationCenter defaultCenter] postNotificationName:NOTIFI_SCHEDULES_UPDATED object:userInfo];
        isSchedulesLoading = NO;
    }];
}



- (void)checkAssistants {
    
    NSLog(@"Cheking assistants..");
    
    if ([UserDefaultsHelper getAnonymous]) {
        NSLog(@"Anonymous user can not view assistants..");
        return;
    }
    
    if (isAssistantsLoading) {
        NSLog(@"Assistants allready loading..");
        return;
    }
    isAssistantsLoading = YES;
    NSManagedObjectContext *globalContext = [self managedObjectContext];
    
    NSManagedObjectContext *managedObjectContextForThread = [[NSManagedObjectContext alloc] initWithConcurrencyType:NSPrivateQueueConcurrencyType];
    [managedObjectContextForThread setPersistentStoreCoordinator:globalContext.persistentStoreCoordinator];

    
    [_api getAssistentsWithOnSuccessHandler:^(NSDictionary *content) {
        [self deleteAllEntitiesWithIdentifier:IDEN_ASSISTANT];
        
        NSArray *data = (NSArray *)content[@"data"][@"assistants"];
        NSLog(@"Total assistants: %lu", (unsigned long)data.count);
        
        NSString *version = content[@"data"][@"version"];
        NSLog(@"Version: %@", version);
        for (NSDictionary *assistantData in data) {
            Assistant *assistant = [NSEntityDescription
                                    insertNewObjectForEntityForName:IDEN_ASSISTANT
                                    inManagedObjectContext:managedObjectContextForThread];
            [JSONToObjectMapper deserializeAllKeys:assistantData withFixedIdValueForObject:@"idAssistant" toObject:assistant];
            
            NSError *error;
            if (![managedObjectContextForThread save:&error]) {
                NSLog(@"Error saving assistant: %@", [error localizedDescription]);
            }
        }
        
        NSError *error;
        if (![globalContext save:&error]) {
            NSLog(@"Error saving globalContext: %@", [error localizedDescription]);
        }
        
        [NSFetchedResultsController deleteCacheWithName:CACHE_ASSISTANTS];
        NSMutableDictionary *userInfo = [[NSMutableDictionary alloc] init];
        userInfo[@"result"] = [NSNumber numberWithBool:YES];
        [[NSNotificationCenter defaultCenter] postNotificationName:NOTIFI_ASSISTANTS_UPDATED object:userInfo];
        isAssistantsLoading = NO;
    } andFailureHandler:^(AFHTTPRequestOperation *operation, NSError *error) {
        //check error and relaunch
        NSMutableDictionary *userInfo = [[NSMutableDictionary alloc] init];
        userInfo[@"result"] = [NSNumber numberWithBool:NO];
        [[NSNotificationCenter defaultCenter] postNotificationName:NOTIFI_ASSISTANTS_UPDATED object:userInfo];
        isAssistantsLoading = NO;
        
    }];
}

- (void)checkTxokos {
    
    NSLog(@"Cheking txokos..");
    
    if (isTxokosLoading) {
        NSLog(@"Txokos allready loading..");
        return;
    }
    isTxokosLoading = YES;
    NSManagedObjectContext *globalContext = [self managedObjectContext];
    
    NSManagedObjectContext *managedObjectContextForThread = [[NSManagedObjectContext alloc] initWithConcurrencyType:NSPrivateQueueConcurrencyType];
    [managedObjectContextForThread setPersistentStoreCoordinator:globalContext.persistentStoreCoordinator];
    
    [_api getTxokosWithOnSuccessHandler:^(NSDictionary *content) {
        [self deleteAllEntitiesWithIdentifier:IDEN_TXOKO];
        
        NSArray *data = (NSArray *)content[@"data"][@"txokos"];
        NSLog(@"Total txokos: %lu", (unsigned long)data.count);
        
        NSString *version = content[@"data"][@"version"];
        NSLog(@"Version: %@", version);
        for (NSDictionary *txokoData in data) {
            Txoko *txoko = [NSEntityDescription
                            insertNewObjectForEntityForName:IDEN_TXOKO
                            inManagedObjectContext:managedObjectContextForThread];
            [JSONToObjectMapper deserializeAllKeys:txokoData withFixedIdValueForObject:@"idTxoko" toObject:txoko];
            NSError *error;
            if (![managedObjectContextForThread save:&error]) {
                NSLog(@"Error saving txoko: %@", [error localizedDescription]);
            }
        }
        NSError *error;
        if (![globalContext save:&error]) {
            NSLog(@"Error saving globalContext: %@", [error localizedDescription]);
        }
        [NSFetchedResultsController deleteCacheWithName:CACHE_TXOKOS];
        
        NSMutableDictionary *userInfo = [[NSMutableDictionary alloc] init];
        userInfo[@"result"] = [NSNumber numberWithBool:YES];
        [[NSNotificationCenter defaultCenter] postNotificationName:NOTIFI_TXOKOS_UPDATED object:userInfo];
        isTxokosLoading = NO;
    } andFailureHandler:^(AFHTTPRequestOperation *operation, NSError *error) {
        //check error and relaunch
        NSMutableDictionary *userInfo = [[NSMutableDictionary alloc] init];
        userInfo[@"result"] = [NSNumber numberWithBool:NO];
        [[NSNotificationCenter defaultCenter] postNotificationName:NOTIFI_TXOKOS_UPDATED object:userInfo];
        isTxokosLoading = NO;
    }];
}

- (void)checkStands {
    
    NSLog(@"Cheking stands..");
    
    if (isStandsLoading) {
        NSLog(@"Stands allready loading..");
        return;
    }
    isStandsLoading = YES;
    NSManagedObjectContext *globalContext = [self managedObjectContext];
    
    NSManagedObjectContext *managedObjectContextForThread = [[NSManagedObjectContext alloc] initWithConcurrencyType:NSPrivateQueueConcurrencyType];
    [managedObjectContextForThread setPersistentStoreCoordinator:globalContext.persistentStoreCoordinator];
    
    [_api getStandsWithOnSuccessHandler:^(NSDictionary *content) {
        [self deleteAllEntitiesWithIdentifier:IDEN_STAND];
        
        NSArray *data = (NSArray *)content[@"data"][@"expositors"];
        NSLog(@"Total stands: %lu", (unsigned long)data.count);
        
        NSString *version = content[@"data"][@"version"];
        NSLog(@"Version: %@", version);
        for (NSDictionary *standData in data) {
            Stand *stand = [NSEntityDescription
                            insertNewObjectForEntityForName:IDEN_STAND
                            inManagedObjectContext:managedObjectContextForThread];
            [JSONToObjectMapper deserializeAllKeys:standData withFixedIdValueForObject:@"idStand" toObject:stand];
            
            NSError *error;
            if (![managedObjectContextForThread save:&error]) {
                NSLog(@"Error saving stand: %@", [error localizedDescription]);
            }
        }
        
        NSError *error;
        if (![globalContext save:&error]) {
            NSLog(@"Error saving globalContext: %@", [error localizedDescription]);
        }
        
        [NSFetchedResultsController deleteCacheWithName:CACHE_STAND];
        
        NSMutableDictionary *userInfo = [[NSMutableDictionary alloc] init];
        userInfo[@"result"] = [NSNumber numberWithBool:YES];
        [[NSNotificationCenter defaultCenter] postNotificationName:NOTIFI_STAND_UPDATED object:userInfo];
        isStandsLoading = NO;
    } andFailureHandler:^(AFHTTPRequestOperation *operation, NSError *error) {
        NSMutableDictionary *userInfo = [[NSMutableDictionary alloc] init];
        userInfo[@"result"] = [NSNumber numberWithBool:NO];
        [[NSNotificationCenter defaultCenter] postNotificationName:NOTIFI_STAND_UPDATED object:userInfo];
        isStandsLoading = NO;
    }];
}

- (void)checkMeetings {
    
    NSLog(@"Cheking meetings..");
    
    if ([UserDefaultsHelper getAnonymous]) {
        NSLog(@"Anonymous user can not view meetings..");
        return;
    }
    
    if (isMeetingsLoading) {
        NSLog(@"Meetings allready loading..");
        return;
    }
    isMeetingsLoading = YES;
    NSManagedObjectContext *globalContext = [self managedObjectContext];
    
    NSManagedObjectContext *managedObjectContextForThread = [[NSManagedObjectContext alloc] initWithConcurrencyType:NSPrivateQueueConcurrencyType];
    [managedObjectContextForThread setPersistentStoreCoordinator:globalContext.persistentStoreCoordinator];

    
    [_api getMeetingsWithOnSuccessHandler:^(NSDictionary *content) {
        [self deleteAllEntitiesWithIdentifier:IDEN_MEETING];
        
        NSArray *data = (NSArray *)content[@"data"][@"meetings"];
        NSLog(@"Total meetings: %lu", (unsigned long)data.count);
        
        NSString *version = content[@"data"][@"version"];
        NSLog(@"Version: %@", version);
        for (NSDictionary *meetingData in data) {
            Meeting *meeting = [NSEntityDescription
                            insertNewObjectForEntityForName:IDEN_MEETING
                            inManagedObjectContext:managedObjectContextForThread];
            [MeetingMapper deserializeSelectedKeysFrom:meetingData toObject:meeting fromContext:managedObjectContextForThread];
            
            NSError *error;
            if (![managedObjectContextForThread save:&error]) {
                NSLog(@"Error saving meeting: %@", [error localizedDescription]);
            }
        }
        [NSFetchedResultsController deleteCacheWithName:CACHE_MEETING];
        
        NSMutableDictionary *userInfo = [[NSMutableDictionary alloc] init];
        userInfo[@"result"] = [NSNumber numberWithBool:YES];
        [[NSNotificationCenter defaultCenter] postNotificationName:NOTIFI_MEETING_UPDATED object:userInfo];
        isMeetingsLoading = NO;
    } andFailureHandler:^(AFHTTPRequestOperation *operation, NSError *error) {
        //check error and relaunch
        NSMutableDictionary *userInfo = [[NSMutableDictionary alloc] init];
        userInfo[@"result"] = [NSNumber numberWithBool:NO];
        [[NSNotificationCenter defaultCenter] postNotificationName:NOTIFI_MEETING_UPDATED object:userInfo];
        isMeetingsLoading = NO;
    }];
}

- (void)checkSponsors {
    
    NSLog(@"Cheking sponsors..");
    
    if (isSponsorsLoading) {
        NSLog(@"Sponsors allready loading..");
        return;
    }
    isSponsorsLoading = YES;
    
    NSManagedObjectContext *globalContext = [self managedObjectContext];
    
    NSManagedObjectContext *managedObjectContextForThread = [[NSManagedObjectContext alloc] initWithConcurrencyType:NSPrivateQueueConcurrencyType];
    [managedObjectContextForThread setPersistentStoreCoordinator:globalContext.persistentStoreCoordinator];
    
    [_api getSponsorsWithOnSuccessHandler:^(NSDictionary *content) {
        [self deleteAllEntitiesWithIdentifier:IDEN_SPONSOR];
        
        NSArray *data = (NSArray *)content[@"data"][@"sponsors"];
        NSLog(@"Total sponsors: %lu", (unsigned long)data.count);
        
        NSString *version = content[@"data"][@"version"];
        NSLog(@"Version: %@", version);
        for (NSDictionary *sponsorData in data) {
            Sponsor *sponsor = [NSEntityDescription
                            insertNewObjectForEntityForName:IDEN_SPONSOR
                            inManagedObjectContext:managedObjectContextForThread];
            [JSONToObjectMapper deserializeAllKeys:sponsorData withFixedIdValueForObject:@"idSponsor" toObject:sponsor];
            
            NSError *error;
            if (![managedObjectContextForThread save:&error]) {
                NSLog(@"Error saving sponsor: %@", [error localizedDescription]);
            }
        }
        
        NSError *error;
        if (![globalContext save:&error]) {
            NSLog(@"Error saving globalContext: %@", [error localizedDescription]);
        }
        
        [NSFetchedResultsController deleteCacheWithName:CACHE_SPONSOR];
        
        NSMutableDictionary *userInfo = [[NSMutableDictionary alloc] init];
        userInfo[@"result"] = [NSNumber numberWithBool:YES];
        [[NSNotificationCenter defaultCenter] postNotificationName:NOTIFI_SPONSOR_UPDATED object:userInfo];
        isSponsorsLoading = NO;
    } andFailureHandler:^(AFHTTPRequestOperation *operation, NSError *error) {
        NSMutableDictionary *userInfo = [[NSMutableDictionary alloc] init];
        userInfo[@"result"] = [NSNumber numberWithBool:NO];
        [[NSNotificationCenter defaultCenter] postNotificationName:NOTIFI_SPONSOR_UPDATED object:userInfo];
        isSponsorsLoading = NO;
    }];
}

- (void)deleteAllEntitiesWithIdentifier:(NSString *)identifier {
    
    NSManagedObjectContext *globalContext = [self managedObjectContext];
    
    NSManagedObjectContext *managedObjectContextForThread = [[NSManagedObjectContext alloc] initWithConcurrencyType:NSPrivateQueueConcurrencyType];
    [managedObjectContextForThread setPersistentStoreCoordinator:globalContext.persistentStoreCoordinator];
    
    
    NSFetchRequest * allSchedules = [[NSFetchRequest alloc] init];
    [allSchedules setEntity:[NSEntityDescription entityForName:identifier inManagedObjectContext:managedObjectContextForThread]];
    [allSchedules setIncludesPropertyValues:NO]; //only fetch the managedObjectID
    
    NSError * error = nil;
    NSArray * schedules = [managedObjectContextForThread executeFetchRequest:allSchedules error:&error];
    //error handling goes here
    for (NSManagedObject * schedule in schedules) {
        [managedObjectContextForThread deleteObject:schedule];
    }
    NSError *saveError = nil;
    [managedObjectContextForThread save:&saveError];
    
    if (![globalContext save:&error]) {
        NSLog(@"Error saving globalContext: %@", [error localizedDescription]);
    }
}

- (void)deleteAllDAtaFromDatabase {
    
//    [self deleteAllEntitiesWithIdentifier:IDEN_SCHEDULE];
    [self deleteAllEntitiesWithIdentifier:IDEN_ASSISTANT];
//    [self deleteAllEntitiesWithIdentifier:IDEN_TXOKO];
//    [self deleteAllEntitiesWithIdentifier:IDEN_STAND];
    [self deleteAllEntitiesWithIdentifier:IDEN_MEETING];
//    [self deleteAllEntitiesWithIdentifier:IDEN_SPONSOR];
    
}

#pragma mark - CoreData

- (void)saveContext {
    
    NSError *error = nil;
    NSManagedObjectContext *managedObjectContext = self.managedObjectContext;
    if (managedObjectContext != nil) {
        if ([managedObjectContext hasChanges] && ![managedObjectContext save:&error]) {
            NSLog(@"Unresolved error %@, %@", error, [error userInfo]);
        }
    }
}

- (NSURL *)applicationDocumentsDirectory
{
    return [[[NSFileManager defaultManager] URLsForDirectory:NSDocumentDirectory inDomains:NSUserDomainMask] lastObject];
}

- (NSManagedObjectContext *)managedObjectContext
{
    if (_managedObjectContext != nil) {
        return _managedObjectContext;
    }
    
    NSPersistentStoreCoordinator *coordinator = [self persistentStoreCoordinator];
    if (coordinator != nil) {
        _managedObjectContext = [[NSManagedObjectContext alloc] initWithConcurrencyType:NSMainQueueConcurrencyType];
        [_managedObjectContext setPersistentStoreCoordinator:coordinator];
    }
    return _managedObjectContext;
}

- (NSManagedObjectModel *)managedObjectModel
{
    if (_managedObjectModel != nil) {
        return _managedObjectModel;
    }
    NSURL *modelURL = [[NSBundle mainBundle] URLForResource:DATAMODEL_NAME withExtension:@"momd"];
    _managedObjectModel = [[NSManagedObjectModel alloc] initWithContentsOfURL:modelURL];
    return _managedObjectModel;
}

- (NSPersistentStoreCoordinator *)persistentStoreCoordinator
{
    if (_persistentStoreCoordinator != nil) {
        return _persistentStoreCoordinator;
    }
    
    NSURL *storeURL = [[self applicationDocumentsDirectory] URLByAppendingPathComponent:DATABASE_NAME];
    
    NSError *error = nil;
    _persistentStoreCoordinator = [[NSPersistentStoreCoordinator alloc] initWithManagedObjectModel:[self managedObjectModel]];
    
    
    NSDictionary *options = [NSDictionary dictionaryWithObjectsAndKeys:
                             [NSNumber numberWithBool:YES], NSMigratePersistentStoresAutomaticallyOption,
                             [NSNumber numberWithBool:YES], NSInferMappingModelAutomaticallyOption, nil];
    
    
    if (![_persistentStoreCoordinator addPersistentStoreWithType:NSSQLiteStoreType
                                                   configuration:nil
                                                             URL:storeURL
                                                         options:options
                                                           error:&error]) {
        NSLog(@"Unresolved error %@, %@", error, [error userInfo]);
    }
    return _persistentStoreCoordinator;
}

@end
