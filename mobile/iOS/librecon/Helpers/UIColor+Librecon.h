//
//  UIColor+Librecon.h
//  librecon
//
//  Created by Sergio Garcia on 18/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface UIColor (Librecon)

+ (UIColor *)colorwithHexString:(NSString *)hexStr alpha:(CGFloat)alpha;

//Image from color
+ (UIImage *)imageWithColor:(UIColor *)color;

//Colors
+ (UIColor *)navigationBarBackgroundColor;
+ (UIColor *)navigationBarBackgroundColorWithAlpha:(CGFloat)alpha;

+ (UIColor *)tabBarSelectedColor;
+ (UIColor *)tabBarUnselectedColor;
+ (UIColor *)grayCustomColor;
+ (UIColor *)tableViewBackgroundTextColor;

//Meetings
+ (UIColor *)disabledColor;

+ (UIColor *)getStatePending;
+ (UIColor *)getStateAccepted;
+ (UIColor *)getStateCancelled;
//Cancell
+ (UIColor *)getCancelBackgroundColor;
+ (UIColor *)getCancelHighlightedBackgroundColor;
//Accept
+ (UIColor *)getAcceptBackgroundColor;
+ (UIColor *)getAcceptHighlightedBackgroundColor;

@end
