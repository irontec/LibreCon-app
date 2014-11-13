//
//  UIColor+Librecon.m
//  librecon
//
//  Created by Sergio Garcia on 18/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import "UIColor+Librecon.h"

@implementation UIColor (Librecon)

//Image from color

+ (UIImage *)imageWithColor:(UIColor *)color {
    
    CGRect rect = CGRectMake(0.0f, 0.0f, 1.0f, 1.0f);
    UIGraphicsBeginImageContext(rect.size);
    CGContextRef context = UIGraphicsGetCurrentContext();
    
    CGContextSetFillColorWithColor(context, [color CGColor]);
    CGContextFillRect(context, rect);
    
    UIImage *image = UIGraphicsGetImageFromCurrentImageContext();
    UIGraphicsEndImageContext();
    
    return image;
}

+ (UIColor *)colorwithHexString:(NSString *)hexStr alpha:(CGFloat)alpha;
{
    //-----------------------------------------
    // Convert hex string to an integer
    //-----------------------------------------
    unsigned int hexint = 0;
    
    // Create scanner
    NSScanner *scanner = [NSScanner scannerWithString:hexStr];
    
    // Tell scanner to skip the # character
    [scanner setCharactersToBeSkipped:[NSCharacterSet
                                       characterSetWithCharactersInString:@"#"]];
    [scanner scanHexInt:&hexint];
    
    //-----------------------------------------
    // Create color object, specifying alpha
    //-----------------------------------------
    UIColor *color =
    [UIColor colorWithRed:((CGFloat) ((hexint & 0xFF0000) >> 16))/255
                    green:((CGFloat) ((hexint & 0xFF00) >> 8))/255
                     blue:((CGFloat) (hexint & 0xFF))/255
                    alpha:alpha];
    
    return color;
}


//Colors

+ (UIColor *)navigationBarBackgroundColor {
    
    return [UIColor colorWithRed:0/255.0 green:169/255.0 blue:191/255.0 alpha:1.0];
}

+ (UIColor *)navigationBarBackgroundColorWithAlpha:(CGFloat)alpha {
    
    return [UIColor colorWithRed:0/255.0 green:169/255.0 blue:191/255.0 alpha:alpha];
}

+ (UIColor *)tabBarSelectedColor {
    
    return [UIColor colorWithRed:0/255.0 green:0/255.0 blue:0/255.0 alpha:0.40];
}

+ (UIColor *)tabBarUnselectedColor {
    
    return [UIColor whiteColor];
}

+ (UIColor *)grayCustomColor {
    
    return [UIColor colorWithRed:126/255.0 green:126/255.0 blue:126/255.0 alpha:1.0];
}

+ (UIColor *)tableViewBackgroundTextColor {
    
    return [UIColor colorWithRed:126/255.0 green:126/255.0 blue:126/255.0 alpha:1.0];
}

//Meetings

+ (UIColor *)disabledColor {
    
    return [self grayCustomColor];
}

+ (UIColor *)getStatePending {
    
    return [UIColor colorWithRed:255/255.0 green:187/255.0 blue:51/255.0 alpha:1.0];
}

+ (UIColor *)getStateAccepted {
    
    return [UIColor colorWithRed:153/255.0 green:204/255.0 blue:0/255.0 alpha:1.0];
}

+ (UIColor *)getStateCancelled {
    
    return [UIColor colorWithRed:255/255.0 green:68/255.0 blue:68/255.0 alpha:1.0];
}

//Cancel

+ (UIColor *)getCancelBackgroundColor {
    
    return [self getStateCancelled];
}

+ (UIColor *)getCancelHighlightedBackgroundColor {
    
    return [UIColor colorWithRed:255/255.0 green:124/255.0 blue:124/255.0 alpha:1.0];
}

//Accept

+ (UIColor *)getAcceptBackgroundColor {
    
    return [self getStateAccepted];
}

+ (UIColor *)getAcceptHighlightedBackgroundColor {
    
    return [UIColor colorWithRed:175/255.0 green:234/255.0 blue:0/255.0 alpha:1.0];
}
@end
