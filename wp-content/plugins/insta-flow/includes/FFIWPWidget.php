<?php namespace ffinst;

use ffinst\flow\db\FFDB;
use ffinst\flow\db\FFDBManager;

if ( ! defined( 'WPINC' ) ) die;
/**
 * FlowFlow.
 *
 * @package   FlowFlow
 * @author    Looks Awesome <email@looks-awesome.com>
 *
 * @link      http://looks-awesome.com
 * @copyright 2014-2016 Looks Awesome
 */
class FFIWPWidget extends \WP_Widget{
	private $context;

	public function __construct($context) {
		$this->context = $context;
		parent::__construct( 'ffi_widget', 'Grace Widget',
			array( 'description' => 'Place your Instagram stream' ) // Args
		);
	}

	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}
		$nst = FFI::get_instance_by_slug('insta-flow');
		echo $nst->renderShortCode(array('id' => $instance['streamId']));
		echo $args['after_widget'];
	}

	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : 'New title';

		//Important!
		//It will be execute before migrations!
		//Need to check exist tables and fields!
		/** @var FFDBManager $dbm */
		$dbm = $this->context['db_manager'];
		$streams = array();
		if (FFDB::existTable($dbm->streams_table_name)) $streams = FFDB::streams($dbm->streams_table_name);

		$value = '';
		if (sizeof($streams) > 0){
			$streamId = null;
			foreach ( $streams as $id => $stream ) {
				if ($streamId == null) $streamId = ! empty( $instance['streamId'] ) ? esc_attr($instance['streamId']) : $id;
				$streamName = 'Stream #' . $id . ( $stream['name'] ? ' - ' . $stream['name'] : '');
				$selected = ($streamId == $id) ? ' selected' : '';
				$value .= "<option value='{$id}'{$selected}>{$streamName}</option>\n";
			}
		}

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'streamId' ); ?>"><?php _e( 'Stream:' ); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'streamId' ); ?>" name="<?php echo $this->get_field_name( 'streamId' ); ?>">
				<?php echo $value; ?>
			</select>
		</p>
	<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['streamId'] = ( ! empty( $new_instance['streamId'] ) ) ? strip_tags( $new_instance['streamId'] ) : '1';

		return $instance;
	}
}