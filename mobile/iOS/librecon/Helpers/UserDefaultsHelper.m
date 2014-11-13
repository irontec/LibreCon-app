//
//  UserDefaultsHelper.m
//  librecon
//
//  Created by Sergio Garcia on 19/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import "UserDefaultsHelper.h"
#import "API.h"


#define CURRENT_LANGUAGE @"currentAppLanguaje"
#define DATA_VERSION @"verionOf"
#define HASH @"hash"
#define USER_PROFILE @"user_profile"
#define ANONYMOUS @"anonymous_user"
#define UUID @"uuid"

@implementation UserDefaultsHelper


+ (void)registerAllDefaults
{
    NSDictionary *dict = [NSDictionary dictionaryWithObjectsAndKeys:
                          @"", HASH,
                          NO, ANONYMOUS,
                          @"", UUID,
                          nil];
    [[NSUserDefaults standardUserDefaults] registerDefaults:dict];
}

+ (void)deleteAllDefaults {
    
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    [defaults setValue:@"" forKey:HASH];
    [defaults setObject:nil forKey:USER_PROFILE];
    [defaults setBool:NO forKey:ANONYMOUS];
//    [defaults setValue:@"" forKey:UUID];
    
    NSString *versionString = [NSString stringWithFormat:@"%@_%@", DATA_VERSION, SCHEDULES];
    [defaults setValue:nil forKey:versionString];
    versionString = [NSString stringWithFormat:@"%@_%@", DATA_VERSION, ASSISTANTS];
    [defaults setValue:nil forKey:versionString];
    versionString = [NSString stringWithFormat:@"%@_%@", DATA_VERSION, TXOKOS];
    [defaults setValue:nil forKey:versionString];
    versionString = [NSString stringWithFormat:@"%@_%@", DATA_VERSION, STANDS];
    [defaults setValue:nil forKey:versionString];
    versionString = [NSString stringWithFormat:@"%@_%@", DATA_VERSION, MEETINGS];
    [defaults setValue:nil forKey:versionString];
    versionString = [NSString stringWithFormat:@"%@_%@", DATA_VERSION, SPONSORS];
    [defaults setValue:nil forKey:versionString];
    
    [defaults synchronize];
    
}

+ (void)setActualLanguage:(NSString *)languaje {
    
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    [defaults setValue:languaje forKey:CURRENT_LANGUAGE];
    [defaults synchronize];
}

+ (NSString *)getActualLanguage {
    
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    NSString *languaje = [defaults stringForKey:CURRENT_LANGUAGE];
    if (!languaje) {
        languaje = @"";
    }
    return languaje;
}

+ (void)setUUID:(NSString *)uuid {
    
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    [defaults setValue:uuid forKey:UUID];
    [defaults synchronize];
}

+ (NSString *)getUUID {
    
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    NSString *uuid = [defaults stringForKey:UUID];
    return uuid;
}

+ (void)setAnonymous:(BOOL)anonymous {
    
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    [defaults setBool:anonymous forKey:ANONYMOUS];
    [defaults synchronize];
}

+ (BOOL)getAnonymous {
    
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    BOOL anonymous = [defaults boolForKey:ANONYMOUS];
    return anonymous;
}

+ (void)setVersion:(NSString *)version forType:(NSString *)type {
    
    NSString *finalString = [NSString stringWithFormat:@"%@_%@", DATA_VERSION, type];
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    [defaults setValue:version forKey:finalString];
    [defaults synchronize];
    
}

+ (NSString *)getVersionForType:(NSString *)type {
    
    NSString *finalString = [NSString stringWithFormat:@"%@_%@", DATA_VERSION, type];
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    NSString *version = [defaults stringForKey:finalString];
    if (!version) {
        version = @"";
    }
    return version;
}

+ (void)setUserHash:(NSString *)userHash {
    
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    [defaults setValue:userHash forKey:HASH];
    [defaults synchronize];
}

+ (NSString *)getUserHash {
    
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    NSString *userHash = [defaults stringForKey:HASH];
    if (!userHash) {
        userHash = @"";
    }
    return userHash;
}

+ (void)setUserData:(User *)user
{
    NSData *encodedUser = [NSKeyedArchiver archivedDataWithRootObject:user];
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    [defaults setObject:encodedUser forKey:USER_PROFILE];
    [defaults synchronize];
}

+ (User *)getUserData
{
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    NSData *encodedUser = [defaults objectForKey:USER_PROFILE];
    User *user = [NSKeyedUnarchiver unarchiveObjectWithData:encodedUser];
    return user;
}

@end
