//
//  MeetingSwitchTableViewCell.h
//  librecon
//
//  Created by Sergio Garcia on 01/10/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface MeetingSwitchTableViewCell : UITableViewCell

@property (weak, nonatomic) IBOutlet UILabel *lblTitle;
@property (weak, nonatomic) IBOutlet UISwitch *switchState;

@end
