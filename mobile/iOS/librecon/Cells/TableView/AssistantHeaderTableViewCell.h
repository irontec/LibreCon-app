//
//  AssistantHeaderTableViewCell.h
//  librecon
//
//  Created by Sergio Garcia on 13/10/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface AssistantHeaderTableViewCell : UITableViewCell

@property (weak, nonatomic) IBOutlet UIImageView *imgProfile;
@property (weak, nonatomic) IBOutlet UILabel *lblCompany;
@property (weak, nonatomic) IBOutlet UILabel *lblPosition;

@end
