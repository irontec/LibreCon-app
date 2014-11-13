//
//  SpeakerViewController.h
//  librecon
//
//  Created by Sergio Garcia on 07/10/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "Speaker.h"

@interface SpeakerViewController : UIViewController <UITableViewDataSource, UITableViewDelegate, UICollectionViewDataSource, UICollectionViewDelegate>

@property (strong, nonatomic) Speaker *speaker;
@property (weak, nonatomic) IBOutlet UITableView *tableView;
@end
