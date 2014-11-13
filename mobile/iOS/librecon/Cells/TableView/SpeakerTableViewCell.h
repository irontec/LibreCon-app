//
//  SpeakerTableViewCell.h
//  librecon
//
//  Created by Sergio Garcia on 23/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface SpeakerTableViewCell : UITableViewCell

@property (weak, nonatomic) IBOutlet UIImageView *imgSpeaker;
@property (weak, nonatomic) IBOutlet UILabel *lblName;
@property (weak, nonatomic) IBOutlet UILabel *lblCompany;
@property (weak, nonatomic) IBOutlet UILabel *lblDescription;
@property (weak, nonatomic) IBOutlet UILabel *lblMore;

@end
