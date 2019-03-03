<?php
/**
 * @version 2.3.0
 * @package JEM
 * @copyright (C) 2013-2019 joomlaeventmanager.net
 * @copyright (C) 2005-2009 Christoph Lukes
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');

JHtml::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.'/helpers/html');
?>

<script type="text/javascript">
	function tableOrdering(order, dir, view)
	{
		var form = document.getElementById("adminForm");

		form.filter_order.value     = order;
		form.filter_order_Dir.value = dir;
		form.submit(view);
	}
</script>

<script type="text/javascript">
	function fullOrdering(id, view)
	{
		var form = document.getElementById("adminForm");
		var field = form.getElementById(id);
		var parts = field.value.split(' ');

		if (parts.length > 1) {
			form.filter_order.value     = parts[0];
			form.filter_order_Dir.value = parts[1];
		}
		form.submit(view);
	}
</script>

<?php
	$sort_by = array();

	$sort_by[] = JHtml::_('select.option', 'a.dates ASC', JText::_('COM_JEM_DATE') . ' ' . JText::_('COM_JEM_ORDER_ASCENDING'));
	$sort_by[] = JHtml::_('select.option', 'a.dates DESC', JText::_('COM_JEM_DATE') . ' ' . JText::_('COM_JEM_ORDER_DESCENDING'));

	if ($this->jemsettings->showtitle == 1) {
		$sort_by[] = JHtml::_('select.option', 'a.title ASC', JText::_('COM_JEM_TITLE') . ' ' . JText::_('COM_JEM_ORDER_ASCENDING'));
		$sort_by[] = JHtml::_('select.option', 'a.title DESC', JText::_('COM_JEM_TITLE') . ' ' . JText::_('COM_JEM_ORDER_DESCENDING'));
	}
	if ($this->jemsettings->showlocate == 1) {
		$sort_by[] = JHtml::_('select.option', 'l.venue ASC', JText::_('COM_JEM_VENUE') . ' ' . JText::_('COM_JEM_ORDER_ASCENDING'));
		$sort_by[] = JHtml::_('select.option', 'l.venue DESC', JText::_('COM_JEM_VENUE') . ' ' . JText::_('COM_JEM_ORDER_DESCENDING'));
	}
	if ($this->jemsettings->showcity == 1) {
		$sort_by[] = JHtml::_('select.option', 'l.city ASC', JText::_('COM_JEM_CITY') . ' ' . JText::_('COM_JEM_ORDER_ASCENDING'));
		$sort_by[] = JHtml::_('select.option', 'l.city DESC', JText::_('COM_JEM_CITY') . ' ' . JText::_('COM_JEM_ORDER_DESCENDING'));
	}
	if ($this->jemsettings->showstate == 1) {
		$sort_by[] = JHtml::_('select.option', 'l.state ASC', JText::_('COM_JEM_STATE') . ' ' . JText::_('COM_JEM_ORDER_ASCENDING'));
		$sort_by[] = JHtml::_('select.option', 'l.state DESC', JText::_('COM_JEM_STATE') . ' ' . JText::_('COM_JEM_ORDER_DESCENDING'));
	}
	if ($this->jemsettings->showcat == 1) {
		$sort_by[] = JHtml::_('select.option', 'c.catname ASC', JText::_('COM_JEM_CATEGORY') . ' ' . JText::_('COM_JEM_ORDER_ASCENDING'));
		$sort_by[] = JHtml::_('select.option', 'c.catname DESC', JText::_('COM_JEM_CATEGORY') . ' ' . JText::_('COM_JEM_ORDER_DESCENDING'));
	}

	$sort_by[] = JHtml::_('select.option', 'r.status ASC', JText::_('COM_JEM_STATUS') . ' ' . JText::_('COM_JEM_ORDER_ASCENDING'));
	$sort_by[] = JHtml::_('select.option', 'r.status DESC', JText::_('COM_JEM_STATUS') . ' ' . JText::_('COM_JEM_ORDER_DESCENDING'));

	$this->lists['sort_by'] = JHtml::_('select.genericlist', $sort_by, 'sort_by', array('size'=>'1','class'=>'inputbox','onchange'=>'fullOrdering(\'sort_by\', \'\');'), 'value', 'text', $this->lists['order'] . ' ' . $this->lists['order_Dir']);
?>

<h2><?php echo JText::_('COM_JEM_REGISTERED_TO'); ?></h2>

<form action="<?php echo htmlspecialchars($this->action); ?>" method="post" id="adminForm" name="adminForm">

	<?php if ($this->settings->get('global_show_filter',1) || $this->settings->get('global_display',1)) : ?>
	<div id="jem_filter" class="floattext">
		<?php if ($this->settings->get('global_show_filter',1)) : ?>
		<div class="jem_fleft">
			<label for="filter"><?php echo JText::_('COM_JEM_FILTER'); ?></label>
			<?php echo $this->lists['filter'].'&nbsp;'; ?>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->lists['search'];?>" class="inputbox" onchange="document.adminForm.submit();" />
			<button class="buttonfilter" type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button class="buttonfilter" type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<?php endif; ?>

		<?php if ($this->settings->get('global_display',1)) : ?>
		<div class="jem_fright">
		<label for="sort_by"><?php echo JText::_('COM_JEM_ORDERING'); ?></label>
		<?php echo $this->lists['sort_by'].' '; ?>
			<label for="limit"><?php echo JText::_('COM_JEM_DISPLAY_NUM'); ?></label>
			<?php echo $this->attending_pagination->getLimitBox(); ?>
		</div>
		<?php endif; ?>
	</div>
	<?php endif; ?>

	<?php
	$hide = (array_key_exists('hide', $this->lists)) ? $this->lists['hide'] : array();
	// calculate span of columns to show, summary must be 12
	$default_span = array('date' => 2, 'title' => 2, 'venue' => 2, 'category' => 2, 'status' => 1, 'comment' => 2);
	$a_span = array('date' => $default_span['date'], 'status' => $default_span['status']); // always shown
	if ($this->jemsettings->showtitle == 1) {
		$a_span['title'] = $default_span['title'];
	}
	if (!array_key_exists('venue', $hide) && (($this->jemsettings->showlocate == 1) || ($this->jemsettings->showcity == 1) || ($this->jemsettings->showstate == 1))) {
		$a_span['venue'] = $default_span['venue'];
	}
	if (!array_key_exists('category', $hide) && ($this->jemsettings->showcat == 1)) {
		$a_span['category'] = $default_span['category'];
	}
	if (!array_key_exists('comment', $hide) && !empty($this->jemsettings->regallowcomments)) {
		$a_span['comment'] = $default_span['comment'];
	}
	$total = array_sum($a_span);
	if (!array_key_exists('title', $a_span) && !array_key_exists('venue', $a_span) && !array_key_exists('category', $a_span) && !array_key_exists('comment', $a_span)) {
		$a_span['date'] += 12 - $total;
	} else {
		while ($total < 12) {
			if (array_key_exists('title', $a_span)) {
				++$a_span['title'];
				++$total;
			}
			if ($total < 12 && ($a_span['date'] <= $default_span['date'])) {
				++$a_span['date'];
				++$total;
			}
			if (($total < 12) && array_key_exists('venue', $a_span)) {
				++$a_span['venue'];
				++$total;
			}
			if (($total < 12) && array_key_exists('comment', $a_span)) {
				++$a_span['comment'];
				++$total;
			}
			if (($total < 12) && array_key_exists('category', $a_span)) {
				++$a_span['category'];
				++$total;
			}
		} // while
	}
	?>

	<div class="eventtable">
		<div class="row-fluid sectiontableheader">
			<div class="span<?php echo $a_span['date']; ?>"><?php echo JText::_('COM_JEM_TABLE_DATE'); ?></div>
			<?php if (array_key_exists('title', $a_span)) : ?>
			<div class="span<?php echo $a_span['title']; ?>"><?php echo JText::_('COM_JEM_TABLE_TITLE'); ?></div>
			<?php endif; ?>
			<?php if (array_key_exists('venue', $a_span)) : ?>
			<div class="span<?php echo $a_span['venue']; ?>"><?php echo JText::_('COM_JEM_TABLE_LOCATION'); ?></div>
			<?php endif; ?>
			<?php if (array_key_exists('category', $a_span)) : ?>
			<div class="span<?php echo $a_span['category']; ?>"><?php echo JText::_('COM_JEM_TABLE_CATEGORY'); ?></div>
			<?php endif; ?>
			<?php if (array_key_exists('status', $a_span)) : ?>
			<div class="span<?php echo $a_span['status']; ?>"><?php echo JText::_('COM_JEM_STATUS'); ?></div>
			<?php endif; ?>
			<?php if (array_key_exists('comment', $a_span)) : ?>
			<div class="span<?php echo $a_span['comment']; ?>"><?php echo JText::_('COM_JEM_COMMENT'); ?></div>
			<?php endif; ?>
		</div>

		<?php if (empty($this->attending)) : ?>
			<div class="row-fluid sectiontableentry<?php echo $this->params->get('pageclass_sfx'); ?>" >
				<div class="span12">
					<strong><i><?php echo JText::_('COM_JEM_NO_EVENTS'); ?></i></strong>
				</div>
			</div>
		<?php else : ?>
			<?php foreach ($this->attending as $row) : ?>
				<?php if (!empty($row->featured)) : ?>
				<div class="row-fluid sectiontableentry featured featured<?php echo $row->id.$this->params->get('pageclass_sfx'); ?>" itemscope="itemscope" itemtype="https://schema.org/Event">
				<?php else : ?>
				<div class="row-fluid sectiontableentry<?php echo $this->params->get('pageclass_sfx'); ?>" itemscope="itemscope" itemtype="https://schema.org/Event">
				<?php endif; ?>

					<div class="span<?php echo $a_span['date']; ?> date">
						<?php
							echo JemOutput::formatShortDateTime($row->dates, $row->times, $row->enddates, $row->endtimes, $this->jemsettings->showtime);
							echo JemOutput::formatSchemaOrgDateTime($row->dates, $row->times, $row->enddates, $row->endtimes);
						?>
					</div>

					<?php if (array_key_exists('title', $a_span)) : ?>
					<div class="span<?php echo $a_span['title']; ?>">
						<?php if (($this->jemsettings->showeventimage == 1) && !empty($row->datimage)) : ?>
						<div class="image">
							<?php echo JemOutput::flyer($row, JemImage::flyercreator($row->datimage, 'event'), 'event'); ?>
						</div>
						<?php endif; ?>
						<?php if ($this->jemsettings->showdetails == 1) : ?>
						<div class="event">
							<a href="<?php echo JRoute::_(JemHelperRoute::getEventRoute($row->slug)); ?>" itemprop="url">
								<span itemprop="name"><?php echo $this->escape($row->title) . JemOutput::recurrenceicon($row); ?></span>
							</a><?php echo JemOutput::publishstateicon($row); ?>
						</div>
						<?php else : ?>
						<div class="event" itemprop="name">
							<?php echo $this->escape($row->title) . JemOutput::recurrenceicon($row) . JemOutput::publishstateicon($row); ?>
						</div>
						<?php endif; ?>
					</div>
					<?php endif; ?>

					<?php if (array_key_exists('venue', $a_span)) : ?>
					<div class="span<?php echo $a_span['venue']; ?> venue">
						<?php
						$venue = array();
						if ($this->jemsettings->showlocate == 1) {
							if (!empty($row->venue)) {
								if (($this->jemsettings->showlinkvenue == 1) && !empty($row->venueslug)) {
									$venue[] = "<a href='".JRoute::_(JemHelperRoute::getVenueRoute($row->venueslug))."'>".$this->escape($row->venue)."</a>";
								} else {
									$venue[] = $this->escape($row->venue);
								}
							} else {
								$venue[] = '-';
							}
						}
						// if no city skip if also no state, else add hyphen
						if (($this->jemsettings->showcity == 1) && (!empty($row->city) || !empty($row->state))) {
							$venue[] = !empty($row->city) ? $this->escape($row->city) : '-';
						}
						if (($this->jemsettings->showstate == 1) && !empty($row->state)) {
							$venue[] = $this->escape($row->state);
						}
						echo implode(', ', $venue);
						?>
					</div>
					<?php endif; ?>

					<?php if (array_key_exists('category', $a_span)) : ?>
					<div class="span<?php echo $a_span['category']; ?> category">
						<?php echo implode(", ", JemOutput::getCategoryList($row->categories, $this->jemsettings->catlinklist)); ?>
					</div>
					<?php endif; ?>

					<?php if (array_key_exists('status', $a_span)) : ?>
					<div class="span<?php echo $a_span['status']; ?> status">
						<?php
						$status = (int)$row->status;
						if ($status === 1 && $row->waiting == 1) { $status = 2; }
						echo JHtml::_('jemhtml.toggleAttendanceStatus', $status, $row->id, false, $this->print);
						?><span class="info-text"><?php
							echo JHtml::_('jemhtml.getAttendanceStatusText', $status, $row->id, false, true);
						?></span>
					</div>
					<?php endif; ?>

					<?php if (array_key_exists('comment', $a_span)) : ?>
					<div class="span<?php echo $a_span['comment']; ?> comment">
						<?php
						$len  = ($this->print) ? 256 : 16;
						$cmnt = (JString::strlen($row->comment) > $len) ? (JString::substr($row->comment, 0, $len - 2).'&hellip;') : $row->comment;
						if (!empty($cmnt)) :
							echo ($this->print) ? $cmnt : JHtml::_('tooltip', $row->comment, null, null, $cmnt, null, null);
						endif;
						?>
					</div>
					<?php endif;?>
				</div>
			<?php endforeach; ?>
	<?php endif; /* noevents */ ?>
	</div>

	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="<?php echo $this->task; ?>" />
	<input type="hidden" name="option" value="com_jem" />
</form>

<div class="pagination">
	<?php echo $this->attending_pagination->getPagesLinks(); ?>
</div>