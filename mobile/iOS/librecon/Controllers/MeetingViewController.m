//
//  MeetingViewController.m
//  librecon
//
//  Created by Sergio Garcia on 22/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import "MeetingViewController.h"
#import "AppDelegate.h"
#import "UIColor+Librecon.h"
#import "UIImageView+AFNetworking.h"
#import "Meeting.h"
#import "MeetingAssistant.h"
#import "MeetingDetailViewController.h"
#import "MeetingMapper.h"
#import "MeetingAcceptCellTableViewCell.h"

@interface MeetingViewController () {
    
    BOOL isLoading;
    UIRefreshControl *refreshControl;
    BOOL isShowingView;
}

@end

@implementation MeetingViewController

- (void)viewDidLoad {
    
    [super viewDidLoad];
    
    if (_meetingId) {
        AppDelegate *del = [UIApplication sharedApplication].delegate;
        NSArray *fetchedObjects;
        NSManagedObjectContext *context = [del managedObjectContext];
        NSFetchRequest *fetch = [[NSFetchRequest alloc] init];
        NSEntityDescription *entityDescription = [NSEntityDescription entityForName:IDEN_MEETING  inManagedObjectContext: context];
        [fetch setEntity:entityDescription];
        [fetch setPredicate:[NSPredicate predicateWithFormat:@"idMeeting == %@ AND customOrder == %d", _meetingId, other]];
        NSError * error = nil;
        fetchedObjects = [context executeFetchRequest:fetch error:&error];
        
        if([fetchedObjects count] == 1) {
            Meeting *meet = [fetchedObjects objectAtIndex:0];
            if (meet && ![meet.status isEqualToString:@"canceled"])
                [self performSegueWithIdentifier:@"openDetail" sender:meet];
        }
    }
    [self menuSetup];
    [self languajeSetup];
    [self viewSetup];
}

- (void)viewWillAppear:(BOOL)animated {
    
    [[NSNotificationCenter defaultCenter] removeObserver:self name:NOTIFI_MEETING_UPDATED object:nil];
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(updateNotificationReceived:) name:NOTIFI_MEETING_UPDATED object:nil];
}

- (void)updateNotificationReceived:(NSNotification*)notification {
    
    NSDictionary *data = [notification object];
    NSNumber *res = data[@"result"];
    if ([res boolValue]) {
        [self dataControllerSetup];
        [_tableView reloadData];
    }
    [refreshControl endRefreshing];
    NSLog(@"Updating meetings datasource..");
    isLoading = NO;
}


- (void)viewDidAppear:(BOOL)animated {
    
    id sectionInfo;
    NSInteger count;
    if ([[_fetchedResultsController sections] count] != 0) {
        sectionInfo = [[_fetchedResultsController sections] objectAtIndex:0];
        count = [sectionInfo numberOfObjects];
    } else {
        count = 0;
    }
    
    if (!count || count == 0) {
        [_tableView setSeparatorStyle:UITableViewCellSeparatorStyleNone];
    } else {
        [_tableView setSeparatorStyle:UITableViewCellSeparatorStyleSingleLine];
    }
    
    AppDelegate *app = (AppDelegate *)[[UIApplication sharedApplication] delegate];
    [app checkMeetings];
    isShowingView = YES;
}

- (void)viewWillDisappear:(BOOL)animated {
    
    isShowingView = NO;
}

- (void)menuSetup {
    
    SWRevealViewController *revealViewController = self.revealViewController;
    if (revealViewController) {
        [self.revealButtonItem setTarget: revealViewController];
        [self.revealButtonItem setAction: @selector( revealToggle: )];
        [self.navigationController.navigationBar addGestureRecognizer:revealViewController.panGestureRecognizer];
    }
}

- (void)languajeSetup {
    
    [self setTitle:NSLocalizedString(@"REUNIONES", nil)];
}

- (void)viewSetup {
    
    [self setBackgroundEmptyView];
    [self dataControllerSetup];
    [self reloadModeSetup];
}

- (void)setBackgroundEmptyView {
    
    // Background View
    UILabel *label = [[UILabel alloc] initWithFrame:_tableView.frame];
    [label setNumberOfLines:4];
    [label setText:NSLocalizedString(@"NO_DATA_MEETING", nil)];
    [label setTextAlignment:NSTextAlignmentCenter];
    [label setTextColor:[UIColor tableViewBackgroundTextColor]];
    [label sizeToFit];
    [_tableView setBackgroundView:label];
}

#pragma mark - Reload Setup

- (void)reloadModeSetup {
    
    refreshControl = [[UIRefreshControl alloc] init];
    [refreshControl setBackgroundColor:[UIColor whiteColor]];
    
    [refreshControl addTarget:self action:@selector(refresh:) forControlEvents:UIControlEventValueChanged];
    NSMutableAttributedString *aString = [[NSMutableAttributedString alloc] initWithString:NSLocalizedString(@"CARGANDO", nil)];
    [aString addAttribute:NSForegroundColorAttributeName value:[UIColor grayCustomColor] range:NSMakeRange(0,aString.length)];
    [refreshControl setAttributedTitle:aString];
    
    [self.tableView addSubview:refreshControl];
}

- (void)refresh:(UIRefreshControl *)refreshControl {
    
    if (isLoading) {
        return;
    }
    isLoading = YES;
    AppDelegate *delegate = (AppDelegate *)[UIApplication sharedApplication].delegate;
    [delegate checkMeetings];
}

#pragma mark - Navigation

- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    
    if ([[segue identifier] isEqualToString:@"openDetail"]) {
        Meeting *meeting = (Meeting *)sender;
        MeetingDetailViewController *meetingDetail = (MeetingDetailViewController *)[segue destinationViewController];
        meetingDetail.meeting = meeting;
    }
}

#pragma mark - Data Control

- (void)dataControllerSetup {
    
    _tableView.dataSource = self;
    _tableView.delegate = self;
    
    AppDelegate *appDelegate = (AppDelegate *)[[UIApplication sharedApplication] delegate];
    
    NSManagedObjectContext *globalContext = [appDelegate managedObjectContext];
    
    _managedObjectContext = [[NSManagedObjectContext alloc] initWithConcurrencyType:NSPrivateQueueConcurrencyType];
    [_managedObjectContext setPersistentStoreCoordinator:globalContext.persistentStoreCoordinator];
    
    NSError *error;
    if (![[self fetchResultsController] performFetch:&error]) {
        NSLog(@"All meetings data. Unresolved error %@, %@", error, [error userInfo]);
    }
}

- (NSFetchedResultsController *)fetchResultsController {
    
    if (_fetchedResultsController != nil) {
        return _fetchedResultsController;
    }
    
    NSFetchRequest *mfetchRequest = [[NSFetchRequest alloc] init];
    NSEntityDescription *mentity = [NSEntityDescription entityForName:IDEN_MEETING
                                               inManagedObjectContext:_managedObjectContext];
    [mfetchRequest setEntity:mentity];
    
    NSSortDescriptor *msort1 = [[NSSortDescriptor alloc]
                                initWithKey:@"customOrder" ascending:YES];
    
    NSSortDescriptor *msort2 = [[NSSortDescriptor alloc]
                                initWithKey:@"createdAt" ascending:NO];
    
    [mfetchRequest setPredicate:[NSPredicate predicateWithFormat:@"customOrder != %d", other]];
    
    [mfetchRequest setSortDescriptors:[NSArray arrayWithObjects:msort1, msort2, nil]];
    [mfetchRequest setFetchBatchSize:20];
    
    NSFetchedResultsController *theFetchedResultsController = [[NSFetchedResultsController alloc] initWithFetchRequest:mfetchRequest
                                                                                                  managedObjectContext:_managedObjectContext
                                                                                                    sectionNameKeyPath:@"customOrder"
                                                                                                             cacheName:CACHE_MEETING];
    
    _fetchedResultsController = theFetchedResultsController;
    
    _fetchedResultsController.delegate = self;
    return _fetchedResultsController;
}

#pragma mark - UITableViewDataSource

- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView {
    
    NSInteger sections = [[_fetchedResultsController sections] count];
    return sections;
}

- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
    
    CGFloat originalSize = 115;
    Meeting *meeting = [_fetchedResultsController objectAtIndexPath:indexPath];
    NSInteger labelHeight = 16;
    switch ([meeting.customOrder integerValue]) {
        case pending: {
            originalSize = originalSize - labelHeight;//location
            originalSize = originalSize - labelHeight;
            if (!meeting.sendedByMe) {
                originalSize = originalSize - labelHeight;
            }
            return originalSize;
            break;
        }
        case accepted: {
            if (!meeting.sendedByMe) {
                originalSize = originalSize - labelHeight;
            }
            return originalSize;
            break;
        }
        case cancelled: {
            originalSize = originalSize - labelHeight;//location
            originalSize = originalSize - labelHeight;
            if (!meeting.sendedByMe) {
                originalSize = originalSize - labelHeight;
            }
            return originalSize;
            break;
        }
    }
    return 0;
}

- (NSString *)tableView:(UITableView *)tableView titleForHeaderInSection:(NSInteger)section {
    
    id sectionInfo = [[_fetchedResultsController sections] objectAtIndex:section];
    NSInteger sectionNameCode = [[sectionInfo name] integerValue];
    
    if (sectionNameCode == pending) {
        return NSLocalizedString(@"PENDIENTES", nil);
    } else if (sectionNameCode == accepted) {
        return NSLocalizedString(@"ACEPTADAS", nil);
    }else if (sectionNameCode == cancelled) {
        return NSLocalizedString(@"CANCELADAS", nil);
    } else {
        return @"";
    }
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
    
    id sectionInfo = [[_fetchedResultsController sections] objectAtIndex:section];
    NSInteger count = [sectionInfo numberOfObjects];
    if (count == 0) {
        [_tableView setSeparatorStyle:UITableViewCellSeparatorStyleNone];
        [_tableView.backgroundView setHidden:NO];
    } else {
        [_tableView setSeparatorStyle:UITableViewCellSeparatorStyleSingleLine];
        [_tableView.backgroundView setHidden:YES];
    }
    return count;
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
    
    MeetingAcceptCellTableViewCell *aCell = [_tableView dequeueReusableCellWithIdentifier:@"meetingAcceptCellTableViewCell"];
    [aCell layoutSubviews];
    [self configureCell:aCell atIndexPath:indexPath];
    
    return aCell;
}

- (void)configureCell:(UITableViewCell *)cell atIndexPath:(NSIndexPath *)indexPath {
    
    CGFloat defaultLblHeight = 16;
    MeetingAcceptCellTableViewCell *mCell = (MeetingAcceptCellTableViewCell *)cell;
    
    Meeting *meeting = [_fetchedResultsController objectAtIndexPath:indexPath];
    MeetingAssistant *assistant = meeting.assistant;
    
    [mCell.imgProfile setImageWithURL:[NSURL URLWithString:assistant.picUrlCircle]
                     placeholderImage:[UIImage imageNamed:@"placeholder_people.png"]];
    
    switch ([meeting.customOrder integerValue]) {
        case pending: {
            mCell.lblLocationHeightConstraint.constant = 0;
            NSAttributedString *state = [[NSAttributedString alloc] initWithString:[NSString stringWithFormat:@"%@: %@", NSLocalizedString(@"ESTADO", nil), NSLocalizedString(@"PENDIENTE", nil)]];
            state = [self changeColor:state
                       selectedString:NSLocalizedString(@"PENDIENTE", nil)
                              toColor:[UIColor getStatePending]];
            [mCell.lblState setAttributedText:state];
            mCell.lblStateHeightConstraint.constant = defaultLblHeight;
            
            [mCell.lblTime setText:[NSString stringWithFormat:@"%@: %@", NSLocalizedString(@"FECHA", nil), (meeting.createdAt ? [self stringFromDate:meeting.createdAt] : @"")]];
            mCell.lblTimeHeightConstraint.constant = defaultLblHeight;
            
            [mCell.lblMoment setText:@""];
            mCell.lblMomentHeightConstraint.constant = 0;
            
            if (meeting.sendedByMe) {
                [mCell.lblOrigin setText:[NSString stringWithFormat:@"%@: %@", NSLocalizedString(@"SOLICITANTE", nil), NSLocalizedString(@"TU", nil)]];
                mCell.lblOriginHeightConstraint.constant = defaultLblHeight;
                [mCell.lblDest setText:[NSString stringWithFormat:@"%@: %@ %@", NSLocalizedString(@"DESTINATARIO", nil), meeting.assistant.name, meeting.assistant.lastName]];
                mCell.lblDestHeightConstraint.constant = defaultLblHeight;
            } else {
                [mCell.lblOrigin setText:[NSString stringWithFormat:@"%@: %@ %@", NSLocalizedString(@"SOLICITANTE", nil), meeting.assistant.name, meeting.assistant.lastName]];
                mCell.lblOriginHeightConstraint.constant = defaultLblHeight;
                [mCell.lblDest setText:@""];
                mCell.lblDestHeightConstraint.constant = 0;
            }
        }
            break;
        case accepted: {
            [mCell.lbllocation setText:NSLocalizedString(@"MEETING_POINT", nil)];
            mCell.lblLocationHeightConstraint.constant = defaultLblHeight;
            
            NSAttributedString *state = [[NSAttributedString alloc] initWithString:[NSString stringWithFormat:@"%@: %@", NSLocalizedString(@"ESTADO", nil), NSLocalizedString(@"ACEPTADA", nil)]];
            state = [self changeColor:state
                       selectedString:NSLocalizedString(@"ACEPTADA", nil)
                              toColor:[UIColor getStateAccepted]];
            [mCell.lblState setAttributedText:state];
            mCell.lblStateHeightConstraint.constant = defaultLblHeight;
            
            [mCell.lblTime setText:[NSString stringWithFormat:@"%@: %@", NSLocalizedString(@"FECHA_RESPUESTA", nil), (meeting.responseDate ? [self stringFromDate:meeting.responseDate] : @"")]];
            mCell.lblTimeHeightConstraint.constant = defaultLblHeight;
            
            [mCell.lblMoment setText:[NSString stringWithFormat:@"%@: %@", NSLocalizedString(@"CUANDO_CELL", nil), [self stringFromMoment:meeting.moment]]];
            mCell.lblMomentHeightConstraint.constant = defaultLblHeight;
            
            if (meeting.sendedByMe) {
                [mCell.lblOrigin setText:[NSString stringWithFormat:@"%@: %@", NSLocalizedString(@"SOLICITANTE", nil), NSLocalizedString(@"TU", nil)]];
                mCell.lblOriginHeightConstraint.constant = defaultLblHeight;
                [mCell.lblDest setText:[NSString stringWithFormat:@"%@: %@ %@", NSLocalizedString(@"DESTINATARIO", nil), meeting.assistant.name, meeting.assistant.lastName]];
                mCell.lblDestHeightConstraint.constant = defaultLblHeight;
            } else {
                [mCell.lblOrigin setText:[NSString stringWithFormat:@"%@: %@ %@", NSLocalizedString(@"SOLICITANTE", nil), meeting.assistant.name, meeting.assistant.lastName]];
                mCell.lblOriginHeightConstraint.constant = defaultLblHeight;
                [mCell.lblDest setText:@""];
                mCell.lblDestHeightConstraint.constant = 0;
            }
        }
            break;
        case cancelled: {
            mCell.lblLocationHeightConstraint.constant = 0;
            NSAttributedString *state = [[NSAttributedString alloc] initWithString:[NSString stringWithFormat:@"%@: %@", NSLocalizedString(@"ESTADO", nil), NSLocalizedString(@"CANCELADA", nil)]];
            state = [self changeColor:state
                       selectedString:NSLocalizedString(@"CANCELADA", nil)
                              toColor:[UIColor getStateCancelled]];
            [mCell.lblState setAttributedText:state];
            mCell.lblStateHeightConstraint.constant = defaultLblHeight;
            
            [mCell.lblTime setText:[NSString stringWithFormat:@"%@: %@", NSLocalizedString(@"FECHA_RESPUESTA", nil), (meeting.responseDate ? [self stringFromDate:meeting.responseDate] : @"")]];
            mCell.lblTimeHeightConstraint.constant = defaultLblHeight;
            
            [mCell.lblMoment setText:@""];
            mCell.lblMomentHeightConstraint.constant = 0;
            
            if (meeting.sendedByMe) {
                [mCell.lblOrigin setText:[NSString stringWithFormat:@"%@: %@", NSLocalizedString(@"SOLICITANTE", nil), NSLocalizedString(@"TU", nil)]];
                mCell.lblOriginHeightConstraint.constant = defaultLblHeight;
                [mCell.lblDest setText:[NSString stringWithFormat:@"%@: %@ %@", NSLocalizedString(@"DESTINATARIO", nil), meeting.assistant.name, meeting.assistant.lastName]];
                mCell.lblDestHeightConstraint.constant = defaultLblHeight;
            } else {
                [mCell.lblOrigin setText:[NSString stringWithFormat:@"%@: %@ %@", NSLocalizedString(@"SOLICITANTE", nil), meeting.assistant.name, meeting.assistant.lastName]];
                mCell.lblOriginHeightConstraint.constant = defaultLblHeight;
                [mCell.lblDest setText:@""];
                mCell.lblDestHeightConstraint.constant = 0;
                
            }
        }
            break;
    }
}

- (NSAttributedString *)changeColor:(NSAttributedString *)aString selectedString:(NSString *)selected toColor:(UIColor *)color {
    
    NSMutableAttributedString *mString = [aString mutableCopy];
    NSRange range=[[aString string] rangeOfString:selected];
    [mString addAttribute:NSForegroundColorAttributeName
                    value:color
                    range:range];
    return mString;
}

- (NSString *)stringFromDate:(NSDate *)date {
    
    NSDateFormatter *dateFormatter = [[NSDateFormatter alloc] init];
    [dateFormatter setDateStyle:NSDateFormatterShortStyle];
    [dateFormatter setTimeStyle:NSDateFormatterShortStyle];
    [dateFormatter setLocale:[NSLocale currentLocale]];
    NSString *dateString = [dateFormatter stringFromDate:date];
    
    return dateString;
}

- (NSString *)stringFromMoment:(NSString *)moment {
    
    if ([moment isEqualToString:@"atRightNow"]) {
        return NSLocalizedString(@"AHORA", nil);
    } else if ([moment isEqualToString:@"atHalfHour"]) {
        return NSLocalizedString(@"MEDIAHORA", nil);
    } else if ([moment isEqualToString:@"atOneHour"]) {
        return NSLocalizedString(@"HORA", nil);
    }
    return @"";
}

#pragma mark - UITableViewDelegate

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
    
    Meeting *meeting = [_fetchedResultsController objectAtIndexPath:indexPath];
    [self performSegueWithIdentifier:@"openDetail" sender:meeting];
    [_tableView deselectRowAtIndexPath:indexPath animated:YES];
}

#pragma mark - NSFetchedResultsControllerDelegate

- (void)controllerWillChangeContent:(NSFetchedResultsController *)controller {
    
    UITableView *tableView = self.tableView;
    [tableView beginUpdates];
    
}

- (void)controller:(NSFetchedResultsController *)controller didChangeObject:(id)anObject atIndexPath:(NSIndexPath *)indexPath forChangeType:(NSFetchedResultsChangeType)type newIndexPath:(NSIndexPath *)newIndexPath {
    
    switch(type) {
        case NSFetchedResultsChangeInsert:
            [self.tableView insertRowsAtIndexPaths:[NSArray arrayWithObject:newIndexPath] withRowAnimation:UITableViewRowAnimationFade];
            break;
            
        case NSFetchedResultsChangeDelete:
            [self.tableView deleteRowsAtIndexPaths:[NSArray arrayWithObject:indexPath] withRowAnimation:UITableViewRowAnimationFade];
            break;
            
        case NSFetchedResultsChangeUpdate:
            [self configureCell:[self.tableView cellForRowAtIndexPath:indexPath] atIndexPath:indexPath];
            break;
            
        case NSFetchedResultsChangeMove:
            [self.tableView deleteRowsAtIndexPaths:[NSArray arrayWithObject:indexPath] withRowAnimation:UITableViewRowAnimationFade];
            [self.tableView insertRowsAtIndexPaths:[NSArray arrayWithObject:newIndexPath] withRowAnimation:UITableViewRowAnimationFade];
            break;
    }
}

- (void)controller:(NSFetchedResultsController *)controller didChangeSection:(id )sectionInfo atIndex:(NSUInteger)sectionIndex forChangeType:(NSFetchedResultsChangeType)type {
    
    switch(type) {
        case NSFetchedResultsChangeInsert:
            [self.tableView insertSections:[NSIndexSet indexSetWithIndex:sectionIndex] withRowAnimation:UITableViewRowAnimationFade];
            break;
            
        case NSFetchedResultsChangeDelete:
            [self.tableView deleteSections:[NSIndexSet indexSetWithIndex:sectionIndex] withRowAnimation:UITableViewRowAnimationFade];
            break;
    }
}

- (void)controllerDidChangeContent:(NSFetchedResultsController *)controller {
    
    [self.tableView endUpdates];
}

#pragma mark - Rotation

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)toInterfaceOrientation
{
    return (toInterfaceOrientation == UIInterfaceOrientationPortrait);
}

- (BOOL)shouldAutorotate
{
    return YES;
}

- (NSUInteger)supportedInterfaceOrientations
{
    return UIInterfaceOrientationMaskPortrait;
}

- (UIInterfaceOrientation)preferredInterfaceOrientationForPresentation
{
    return UIInterfaceOrientationPortrait;
}

@end
