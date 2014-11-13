//
//  AboutViewController.h
//  librecon
//
//  Created by Sergio Garcia on 16/10/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "SWRevealViewController.h"
#import "ParentViewController.h"

@interface AboutViewController : ParentViewController <UIWebViewDelegate>

@property (weak, nonatomic) IBOutlet UIBarButtonItem *revealButtonItem;
@property (weak, nonatomic) IBOutlet UIWebView *webView;
@end
