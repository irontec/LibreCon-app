//
//  AssistantDetailViewController.h
//  librecon
//
//  Created by Sergio Garcia on 18/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "Assistant.h"

@interface AssistantDetailViewController : UIViewController <UITableViewDataSource, UITableViewDelegate>

@property (nonatomic, strong) Assistant *assistant;

@property (weak, nonatomic) IBOutlet UITableView *tableView;
@end
