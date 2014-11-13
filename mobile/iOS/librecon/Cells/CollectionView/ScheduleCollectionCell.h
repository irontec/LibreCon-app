//
//  ScheduleCollectionCell.h
//  librecon
//
//  Created by Sergio Garcia on 17/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface ScheduleCollectionCell : UICollectionViewCell

@property (weak, nonatomic) IBOutlet UIImageView *imgBackground;
@property (weak, nonatomic) IBOutlet UIView *viewColor;
@property (weak, nonatomic) IBOutlet UILabel *lblLocation;
@property (weak, nonatomic) IBOutlet UILabel *lblTitle;
@property (weak, nonatomic) IBOutlet NSLayoutConstraint *lblTitleHeightconstraint;
@property (weak, nonatomic) IBOutlet UILabel *lblSpeaker;
@property (weak, nonatomic) IBOutlet UILabel *lblDate;

@end
