//
//  RotationNavigationController.m
//  librecon
//
//  Created by Sergio Garcia on 16/10/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import "RotationNavigationController.h"

@interface RotationNavigationController ()

@end

@implementation RotationNavigationController

- (BOOL)shouldAutorotate {
    
    if (self.topViewController != nil)
        return [self.topViewController shouldAutorotate];
    else
        return [super shouldAutorotate];
}

- (NSUInteger)supportedInterfaceOrientations {

    if (self.topViewController != nil)
        return [self.topViewController supportedInterfaceOrientations];
    else
        return [super supportedInterfaceOrientations];
}

- (UIInterfaceOrientation)preferredInterfaceOrientationForPresentation {
    
    if (self.topViewController != nil)
        return [self.topViewController preferredInterfaceOrientationForPresentation];
    else
        return [super preferredInterfaceOrientationForPresentation];
}

@end
