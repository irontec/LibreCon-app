//
//  AppDelegate.h
//  librecon
//
//  Created by Sergio Garcia on 15/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface AppDelegate : UIResponder <UIApplicationDelegate>

@property (strong, nonatomic) UIWindow *window;

#pragma mark - Load Controllers
- (void)loadLoginController;
- (void)loadMainController;

#pragma mark - CoreData
@property (readonly, strong, nonatomic) NSManagedObjectContext *managedObjectContext;
@property (readonly, strong, nonatomic) NSManagedObjectModel *managedObjectModel;
@property (readonly, strong, nonatomic) NSPersistentStoreCoordinator *persistentStoreCoordinator;

- (void)saveContext;
- (NSURL *)applicationDocumentsDirectory;

#pragma mark - Data from API
//ALL
- (void)checkDataState;

//INDIVIDUAL
- (void)checkSchedules;
- (void)checkAssistants;
- (void)checkTxokos;
- (void)checkStands;
- (void)checkMeetings;
- (void)checkSponsors;

//DELETE
- (void)deleteAllDAtaFromDatabase;

@end
