//
//  SponsorsViewController.m
//  librecon
//
//  Created by Sergio Garcia on 29/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import "SponsorsViewController.h"
#import "AppDelegate.h"
#import "UIColor+Librecon.h"
#import "SponsorTableViewCell.h"
#import "Sponsor.h"
#import "UIImageView+AFNetworking.h"
#import "UserDefaultsHelper.h"

@interface SponsorsViewController () {
    
    NSString *appLanguaje;
    BOOL isLoading;
    UIRefreshControl *refreshControl;
}

@end

@implementation SponsorsViewController

- (void)viewDidLoad {
    
    [super viewDidLoad];
    appLanguaje = [UserDefaultsHelper getActualLanguage];
    [self menuSetup];
    [self languajeSetup];
    [self viewSetup];
}

- (void)viewWillAppear:(BOOL)animated {
    
    [[NSNotificationCenter defaultCenter] removeObserver:self
                                                    name:NOTIFI_SPONSOR_UPDATED
                                                  object:nil];
    [[NSNotificationCenter defaultCenter] addObserver:self
                                             selector:@selector(updateNotificationReceived:)
                                                 name:NOTIFI_SPONSOR_UPDATED
                                               object:nil];
}

- (void)updateNotificationReceived:(NSNotification*)notification {
    
    NSDictionary *data = [notification object];
    NSNumber *res = data[@"result"];
    if ([res boolValue]) {
        [self dataControllerSetup];
        [_tableView reloadData];
    }
    isLoading = NO;
    [refreshControl endRefreshing];
}

- (void)viewDidAppear:(BOOL)animated {
    
    id sectionInfo = [[_fetchedResultsController sections] objectAtIndex:0];
    NSInteger count = [sectionInfo numberOfObjects];
    
    if (count == 0) {
        _tableView.contentOffset = CGPointMake(0, - refreshControl.frame.size.height);
        [refreshControl beginRefreshing];
    }
    AppDelegate *app = (AppDelegate *)[[UIApplication sharedApplication] delegate];
    [app checkSponsors];
}

- (void)viewWillDisappear:(BOOL)animated {
    
    [[NSNotificationCenter defaultCenter] removeObserver:self name:NOTIFI_SPONSOR_UPDATED object:nil];
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
    
    [self setTitle:NSLocalizedString(@"PATROCINADORES", nil)];
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
    [label setText:NSLocalizedString(@"NO_DATA_SPONSOR", nil)];
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
    [delegate checkSponsors];
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
        NSLog(@"All sponsor data. Unresolved error %@, %@", error, [error userInfo]);
    }
}

- (NSFetchedResultsController *)fetchResultsController {
    
    if (_fetchedResultsController != nil) {
        return _fetchedResultsController;
    }
    
    NSFetchRequest *mfetchRequest = [[NSFetchRequest alloc] init];
    NSEntityDescription *mentity = [NSEntityDescription entityForName:IDEN_SPONSOR
                                               inManagedObjectContext:_managedObjectContext];
    [mfetchRequest setEntity:mentity];
    
    NSString *order = @"orderField";
    NSSortDescriptor *msort1 = [[NSSortDescriptor alloc] initWithKey:order
                                                           ascending:YES];
    
    [mfetchRequest setSortDescriptors:[NSArray arrayWithObjects:msort1, nil]];
     
    [mfetchRequest setFetchBatchSize:20];
    
    NSFetchedResultsController *theFetchedResultsController = [[NSFetchedResultsController alloc] initWithFetchRequest:mfetchRequest
                                                                                                  managedObjectContext:_managedObjectContext
                                                                                                    sectionNameKeyPath:nil
                                                                                                             cacheName:CACHE_SPONSOR];
    
    _fetchedResultsController = theFetchedResultsController;
    
    _fetchedResultsController.delegate = self;
    return _fetchedResultsController;
}

- (void)openUrlInBrowser:(NSString *)mUrl {
    
    NSURL *url = [NSURL URLWithString:mUrl];
    [[UIApplication sharedApplication] openURL:url];
}

#pragma mark - UITableViewDataSource


- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
    
    id sectionInfo = [[_fetchedResultsController sections] objectAtIndex:section];
    NSInteger count = [sectionInfo numberOfObjects];
    if (count == 0) {
        [_tableView.backgroundView setHidden:NO];
    } else {
        [_tableView.backgroundView setHidden:YES];
    }
    return count;
}

- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
    
    CGFloat val = (_tableView.frame.size.width / 3) * 2;//600*400
    return val;
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
    
    SponsorTableViewCell *cell = [_tableView dequeueReusableCellWithIdentifier:@"sponsorTableViewCell"];
    if (!cell) {
        cell = [[SponsorTableViewCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:@"sponsorTableViewCell"];
    }
    [self configureCell:cell atIndexPath:indexPath];
    
    return cell;
}

- (void)configureCell:(UITableViewCell *)cell atIndexPath:(NSIndexPath *)indexPath {
    
    Sponsor *sponsor = [_fetchedResultsController objectAtIndexPath:indexPath];
    SponsorTableViewCell *mCell = (SponsorTableViewCell *)cell;
    [mCell.imgSponsor setImageWithURL:[NSURL URLWithString:sponsor.picUrl]
                     placeholderImage:[UIImage imageNamed:@"placeholder_librecon.png"]];
    
    if ([appLanguaje isEqualToString:@"en"]) {
        [mCell.lblName setText:sponsor.name_en];
    } else if ([appLanguaje isEqualToString:@"eu"]) {
        [mCell.lblName setText:sponsor.name_eu];
    } else {
        [mCell.lblName setText:sponsor.name_es];
    }
    [mCell.lblUrl setText:sponsor.url];
}

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
    
    Sponsor *sponsor = [_fetchedResultsController objectAtIndexPath:indexPath];
    [self openUrlInBrowser:sponsor.url];
    [tableView deselectRowAtIndexPath:indexPath animated:YES];
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
