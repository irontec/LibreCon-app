//
//  AssistantsViewController.m
//  librecon
//
//  Created by Sergio Garcia on 15/09/14.
//  Copyright (c) 2014 Sergio Garcia. All rights reserved.
//

#import "AssistantsViewController.h"
#import "AppDelegate.h"
#import "Assistant.h"
#import "AssistantTableViewCell.h"
#import "UIImageView+AFNetworking.h"
#import "AssistantDetailViewController.h"
#import "UIColor+Librecon.h"

@interface AssistantsViewController () {
    
    BOOL isLoading;
    UIRefreshControl *refreshControl;
}

@end

@implementation AssistantsViewController

- (void)viewDidLoad {
    
    [super viewDidLoad];
    
    [self menuSetup];
    [self languajeSetup];
    [self viewSetup];
}

- (void)viewWillAppear:(BOOL)animated {
    
    [[NSNotificationCenter defaultCenter] removeObserver:self
                                                    name:NOTIFI_ASSISTANTS_UPDATED
                                                  object:nil];
    [[NSNotificationCenter defaultCenter] addObserver:self
                                             selector:@selector(updatenotificationReceived:)
                                                 name:NOTIFI_ASSISTANTS_UPDATED
                                               object:nil];
}

- (void)updatenotificationReceived:(NSNotification*)notification {
    
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
    
    if (!count || count == 0) {
        [_tableView setSeparatorStyle:UITableViewCellSeparatorStyleNone];
    } else {
        [_tableView setSeparatorStyle:UITableViewCellSeparatorStyleSingleLine];
    }
    
    if (count == 0) {
        _tableView.contentOffset = CGPointMake(0, - refreshControl.frame.size.height);
        [refreshControl beginRefreshing];
    }
    AppDelegate *app = (AppDelegate *)[[UIApplication sharedApplication] delegate];
    [app checkAssistants];
}

- (void)viewWillDisappear:(BOOL)animated {
    
    [[NSNotificationCenter defaultCenter] removeObserver:self name:NOTIFI_ASSISTANTS_UPDATED object:nil];
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
    [self.searchDisplayController.searchBar setPlaceholder:NSLocalizedString(@"BUSCAR", nil)];
    [self setTitle:NSLocalizedString(@"ASISTENTES", nil)];
}

- (void)viewSetup {
    
    [self setBackgroundEmptyView];
    [self dataControllerSetup];
    [self reloadModeSetup];
    
    id sectionInfo = [[_fetchedResultsController sections] objectAtIndex:0];
    NSInteger count = [sectionInfo numberOfObjects];
    if (count > 0) {
        NSIndexPath *indexPat = [NSIndexPath indexPathForRow:0 inSection:0];
        [_tableView scrollToRowAtIndexPath:indexPat atScrollPosition:UITableViewScrollPositionTop animated:NO];
    }
}

- (void)setBackgroundEmptyView {
    
    // Background View
    UILabel *label = [[UILabel alloc] initWithFrame:_tableView.frame];
    [label setNumberOfLines:4];
    [label setText:NSLocalizedString(@"NO_DATA_ASSISTANT", nil)];
    [label setTextAlignment:NSTextAlignmentCenter];
    [label setTextColor:[UIColor tableViewBackgroundTextColor]];
    [label sizeToFit];
    [_tableView setBackgroundView:label];
}

#pragma mark - Reload Setup

- (void)reloadModeSetup {
    
    refreshControl = [[UIRefreshControl alloc] init];
    [refreshControl setBackgroundColor:[UIColor whiteColor]];
    
    [refreshControl addTarget:self
                       action:@selector(refresh:)
             forControlEvents:UIControlEventValueChanged];
    NSMutableAttributedString *aString = [[NSMutableAttributedString alloc] initWithString:NSLocalizedString(@"CARGANDO", nil)];
    [aString addAttribute:NSForegroundColorAttributeName
                    value:[UIColor grayCustomColor]
                    range:NSMakeRange(0,aString.length)];
    [refreshControl setAttributedTitle:aString];
    
    [self.tableView addSubview:refreshControl];
}

- (void)refresh:(UIRefreshControl *)refreshControl {
    
    if (isLoading) {
        return;
    }
    isLoading = YES;
    AppDelegate *delegate = (AppDelegate *)[UIApplication sharedApplication].delegate;
    [delegate checkAssistants];
}

#pragma mark - Navigation

- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    
    if ([[segue identifier] isEqualToString:@"openDetail"]) {
        Assistant *assistant = (Assistant *)sender;
        AssistantDetailViewController *assistantDetail = (AssistantDetailViewController *)[segue destinationViewController];
        assistantDetail.assistant = assistant;
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
        NSLog(@"All assistants data. Unresolved error %@, %@", error, [error userInfo]);
    }
}

- (NSFetchedResultsController *)fetchResultsController {
    
    if (_fetchedResultsController != nil) {
        return _fetchedResultsController;
    }
    NSFetchRequest *mfetchRequest = [[NSFetchRequest alloc] init];
    NSEntityDescription *mentity = [NSEntityDescription entityForName:IDEN_ASSISTANT
                                               inManagedObjectContext:_managedObjectContext];
    [mfetchRequest setEntity:mentity];
    [mfetchRequest setFetchBatchSize:20];
    
    NSSortDescriptor *msort = [[NSSortDescriptor alloc]
                               initWithKey:@"name" ascending:YES];
    [mfetchRequest setSortDescriptors:[NSArray arrayWithObject:msort]];
    
    NSFetchedResultsController *theFetchedResultsController = [[NSFetchedResultsController alloc] initWithFetchRequest:mfetchRequest
                                                                                                  managedObjectContext:_managedObjectContext
                                                                                                    sectionNameKeyPath:nil
                                                                                                             cacheName:nil];
    
    _fetchedResultsController = theFetchedResultsController;
    
    _fetchedResultsController.delegate = self;
    return _fetchedResultsController;
}

- (NSFetchedResultsController *)filteredResultsController {
    
    [NSFetchedResultsController deleteCacheWithName:@"FilteredAssistans"];
    NSFetchRequest *mfetchRequest = [[NSFetchRequest alloc] init];
    NSEntityDescription *mentity = [NSEntityDescription entityForName:IDEN_ASSISTANT
                                               inManagedObjectContext:_managedObjectContext];
    [mfetchRequest setEntity:mentity];
    
    NSSortDescriptor *msort = [[NSSortDescriptor alloc]
                               initWithKey:@"name" ascending:YES];
    [mfetchRequest setSortDescriptors:[NSArray arrayWithObject:msort]];
    [mfetchRequest setFetchBatchSize:20];
    
    NSString *userFilter = [self.searchDisplayController.searchBar text];
    if (userFilter && [userFilter length] > 0) {
        NSPredicate *predicate = [NSPredicate predicateWithFormat:@"name contains[cd] %@ OR lastName contains[cd] %@ OR company contains[cd] %@ OR interests contains[cd] %@", userFilter, userFilter, userFilter, userFilter];
        [mfetchRequest setPredicate:predicate];
    }
    
    NSFetchedResultsController *theFetchedResultsController = [[NSFetchedResultsController alloc] initWithFetchRequest:mfetchRequest
                                                                                                  managedObjectContext:_managedObjectContext
                                                                                                    sectionNameKeyPath:nil
                                                                                                             cacheName:@"FilteredAssistans"];
    
    _filteredResultsController = theFetchedResultsController;
    
    _filteredResultsController.delegate = self;
    return _filteredResultsController;
}

#pragma mark - UITableViewDataSource

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
    
    id sectionInfo;
    NSInteger count;
    if (tableView == self.searchDisplayController.searchResultsTableView) {
        sectionInfo = [[_filteredResultsController sections] objectAtIndex:section];
        count = [sectionInfo numberOfObjects];
    } else {
        sectionInfo = [[_fetchedResultsController sections] objectAtIndex:section];
        count = [sectionInfo numberOfObjects];
        if (count == 0) {
            [_tableView.backgroundView setHidden:NO];
        } else {
            [_tableView.backgroundView setHidden:YES];
        }
    }
    return count;
}

- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
    
    return 65;
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
    
    AssistantTableViewCell *cell = [_tableView dequeueReusableCellWithIdentifier:@"assistantTableViewCell"];
    if (!cell) {
        cell = [[AssistantTableViewCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:@"assistantTableViewCell"];
    }
    [self configureCell:cell atIndexPath:indexPath inTableView:tableView];
    
    return cell;
}

- (void)configureCell:(UITableViewCell *)cell atIndexPath:(NSIndexPath *)indexPath inTableView:(UITableView *)tableView {
    
    AssistantTableViewCell *mCell = (AssistantTableViewCell *)cell;
    
    Assistant *assistant;
    if (tableView == self.searchDisplayController.searchResultsTableView) {
        assistant = [_filteredResultsController objectAtIndexPath:indexPath];
    } else {
        assistant = [_fetchedResultsController objectAtIndexPath:indexPath];
    }
    
    [mCell.imgProfile setImageWithURL:[NSURL URLWithString:assistant.picUrlCircle] placeholderImage:[UIImage imageNamed:@"placeholder_people.png"]];
    [mCell.lblName setText:[NSString stringWithFormat:@"%@ %@", assistant.name, assistant.lastName]];
    [mCell.lblCompany setText:assistant.company];
    [mCell.lblPosition setText:assistant.position];
    [mCell.lblPosition setTextColor:[UIColor grayCustomColor]];
}

#pragma mark - UITableViewDelegate

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
    
    Assistant *assistant;
    if (tableView == self.searchDisplayController.searchResultsTableView) {
        assistant = [_filteredResultsController objectAtIndexPath:indexPath];
    } else {
        assistant = [_fetchedResultsController objectAtIndexPath:indexPath];
    }
    [self performSegueWithIdentifier:@"openDetail" sender:assistant];
    [_tableView deselectRowAtIndexPath:indexPath animated:YES];
}

#pragma mark - NSFetchedResultsControllerDelegate

- (void)controllerWillChangeContent:(NSFetchedResultsController *)controller {
    
    UITableView *tableView = controller == self.fetchedResultsController ? self.tableView : self.searchDisplayController.searchResultsTableView;
    [tableView beginUpdates];
    
}

- (void)controller:(NSFetchedResultsController *)controller didChangeObject:(id)anObject atIndexPath:(NSIndexPath *)indexPath forChangeType:(NSFetchedResultsChangeType)type newIndexPath:(NSIndexPath *)newIndexPath {
    
    
    UITableView *tableView = controller == self.fetchedResultsController ? self.tableView : self.searchDisplayController.searchResultsTableView;
    
    switch(type) {
            
        case NSFetchedResultsChangeInsert:
            [tableView insertRowsAtIndexPaths:[NSArray arrayWithObject:newIndexPath] withRowAnimation:UITableViewRowAnimationFade];
            break;
            
        case NSFetchedResultsChangeDelete:
            [tableView deleteRowsAtIndexPaths:[NSArray arrayWithObject:indexPath] withRowAnimation:UITableViewRowAnimationFade];
            break;
            
        case NSFetchedResultsChangeUpdate:
            [self configureCell:[tableView cellForRowAtIndexPath:indexPath] atIndexPath:indexPath inTableView:tableView];
            break;
            
        case NSFetchedResultsChangeMove:
            [tableView deleteRowsAtIndexPaths:[NSArray arrayWithObject:indexPath] withRowAnimation:UITableViewRowAnimationFade];
            [tableView insertRowsAtIndexPaths:[NSArray arrayWithObject:newIndexPath] withRowAnimation:UITableViewRowAnimationFade];
            break;
    }
}

- (void)controller:(NSFetchedResultsController *)controller didChangeSection:(id )sectionInfo atIndex:(NSUInteger)sectionIndex forChangeType:(NSFetchedResultsChangeType)type {
    
    UITableView *tableView = controller == self.fetchedResultsController ? self.tableView : self.searchDisplayController.searchResultsTableView;
    switch(type) {
            
        case NSFetchedResultsChangeInsert:
            [tableView insertSections:[NSIndexSet indexSetWithIndex:sectionIndex] withRowAnimation:UITableViewRowAnimationFade];
            break;
            
        case NSFetchedResultsChangeDelete:
            [tableView deleteSections:[NSIndexSet indexSetWithIndex:sectionIndex] withRowAnimation:UITableViewRowAnimationFade];
            break;
    }
}

- (void)controllerDidChangeContent:(NSFetchedResultsController *)controller
{
    UITableView *tableView = controller == self.fetchedResultsController ? self.tableView : self.searchDisplayController.searchResultsTableView;
    [tableView endUpdates];
}

#pragma mark - SearchBarDelegate & Data Filtering

-(BOOL)searchDisplayController:(UISearchDisplayController *)controller shouldReloadTableForSearchString:(NSString *)searchString {
    
    [self filterContentForSearchText:searchString
                               scope:[self.searchDisplayController.searchBar scopeButtonTitles][[self.searchDisplayController.searchBar selectedScopeButtonIndex]]];
    return YES;
}

- (void)filterContentForSearchText:(NSString*)searchText scope:(NSString*)scope {
    
    NSError *error;
    if (![[self filteredResultsController] performFetch:&error]) {
        NSLog(@"Filtered data. Unresolved error %@, %@", error, [error userInfo]);
    }
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
