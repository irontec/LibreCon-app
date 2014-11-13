//
//  main.m
//  librecon
//
//  Created by Sergio Garcia on 15/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import <UIKit/UIKit.h>

#import "AppDelegate.h"
#import "UserDefaultsHelper.h"

int main(int argc, char * argv[])
{
    @autoreleasepool {
        NSLog(@"initial: preferredLanguages: %@", [NSLocale preferredLanguages]);
        [[NSUserDefaults standardUserDefaults] removeObjectForKey:@"AppleLanguages"];
        [[NSUserDefaults standardUserDefaults] synchronize];
        NSLog(@"removed: preferredLanguages: %@", [NSLocale preferredLanguages]);
        NSString *myLang;
        
        NSLocale *locale = [NSLocale currentLocale];
        NSString *regionCode = [locale objectForKey: NSLocaleLanguageCode];
        NSLog(@"regionCode: %@", regionCode);
        
        if ([regionCode isEqualToString:@"eu"]) {
            NSMutableArray *array = [[NSMutableArray alloc] initWithArray:[NSLocale preferredLanguages]];
            [array insertObject:@"eu" atIndex:0];
            
            [[NSUserDefaults standardUserDefaults] setObject:array forKey:@"AppleLanguages"];
            [[NSUserDefaults standardUserDefaults] synchronize];
            myLang = @"eu";
            NSLog(@"eu setted: preferredLanguages: %@", [NSLocale preferredLanguages]);
        } else {
            myLang = [[NSLocale preferredLanguages] objectAtIndex:0];
        }
        NSLog(@"myLang: %@", myLang);
        [UserDefaultsHelper setActualLanguage:myLang];
        NSLog(@"finish: preferredLanguages: %@", [NSLocale preferredLanguages]);
        
        return UIApplicationMain(argc, argv, nil, NSStringFromClass([AppDelegate class]));
    }
}
