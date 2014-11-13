//
//  UserDefaultsHelper.h
//  librecon
//
//  Created by Sergio Garcia on 19/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "User.h"

@interface UserDefaultsHelper : NSObject

+ (void)registerAllDefaults;

+ (void)deleteAllDefaults;

+ (void)setActualLanguage:(NSString *)languaje;
+ (NSString *)getActualLanguage;

+ (void)setUUID:(NSString *)uuid;
+ (NSString *)getUUID;

+ (void)setAnonymous:(BOOL)anonymous;
+ (BOOL)getAnonymous;

+ (void)setVersion:(NSString *)version forType:(NSString *)type;
+ (NSString *)getVersionForType:(NSString *)type;

+ (void)setUserHash:(NSString *)userHash;
+ (NSString *)getUserHash;

+ (void)setUserData:(User *)user;
+ (User *)getUserData;
@end
