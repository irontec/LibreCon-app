//
//  LocationDetailViewController.h
//  librecon
//
//  Created by Sergio Garcia on 20/10/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface LocationDetailViewController : UIViewController <UITableViewDataSource, UITableViewDelegate>

@property (weak, nonatomic) IBOutlet UITableView *tableView;

@property (strong, nonatomic) NSString *picUrl;
@property (strong, nonatomic) NSString *detailTitle;
@property (strong, nonatomic) NSString *text;
@end
