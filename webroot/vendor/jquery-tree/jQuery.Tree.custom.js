/**
 * jQuery Tree Control
 *
 * @author Maxim Vasiliev
 */
(function($) {

	var CLASS_JQUERY_TREE_WRAPPER = 'jquery-tree-wrapper';
	var CLASS_JQUERY_TREE = 'jquery-tree';
	var CLASS_JQUERY_TREE_CONTROLS = 'jquery-tree-controls';
	var CLASS_JQUERY_TREE_COLLAPSE_ALL = 'jquery-tree-collapseall';
	var CLASS_JQUERY_TREE_EXPAND_ALL = 'jquery-tree-expandall';
	var CLASS_JQUERY_TREE_COLLAPSED = 'jquery-tree-collapsed';
	var CLASS_JQUERY_TREE_HANDLE = 'jquery-tree-handle';
	var CLASS_JQUERY_TREE_TITLE = 'jquery-tree-title';
	var CLASS_JQUERY_TREE_NODE = 'jquery-tree-node';
	var CLASS_JQUERY_TREE_LEAF = 'jquery-tree-leaf';
	var CLASS_JQUERY_TREE_CHECKED = 'jquery-tree-checked';
	var CLASS_JQUERY_TREE_UNCHECKED = 'jquery-tree-unchecked';
	var CLASS_JQUERY_TREE_FOUNDED = 'jquery-tree-founded';
	var CLASS_JQUERY_TREE_CHECKED_PARTIAL = 'jquery-tree-checked-partial';
	var CLASS_JQUERY_TREE_SEARCH = 'jquery-tree-search';
	var CLASS_JQUERY_TREE_SEARCH_BTN = 'jquery-tree-search-btn';
	var CLASS_JQUERY_TREE_SEARCH_CLEAR_BTN = 'jquery-tree-search-clear-btn';

	var COLLAPSE_ALL_CODE = '<button class="' + CLASS_JQUERY_TREE_COLLAPSE_ALL + ' btn" type="button">Collapse all</button>';
	var EXPAND_ALL_CODE = '<button class="' + CLASS_JQUERY_TREE_EXPAND_ALL + ' btn" type="button">Expand all</button>';
	var SEARCH_IN_TREE = '<input type="text" class="' + CLASS_JQUERY_TREE_SEARCH + '" />' +
			'<button type="button" class="' + 'btn btn-primary ' + CLASS_JQUERY_TREE_SEARCH_BTN + '">Search</button>' +
			'<button type="button" class="' + CLASS_JQUERY_TREE_SEARCH_CLEAR_BTN + ' btn">Clear</button>';

	var TREE_CONTROLS_CODE = '<div class="' + CLASS_JQUERY_TREE_CONTROLS + '" style="text-align:right;"><form class="form-search">' +
			SEARCH_IN_TREE +
			COLLAPSE_ALL_CODE +
			EXPAND_ALL_CODE +
			'</form></div>';

	var TREE_NODE_HANDLE_COLLAPSED = "+";
	var TREE_NODE_HANDLE_EXPANDED = "&minus;";
	var TREE_NODE_HANDLE_CODE_COLLAPSED = '<span class="' + CLASS_JQUERY_TREE_HANDLE + ' badge">' + TREE_NODE_HANDLE_COLLAPSED + '</span>';
	var TREE_NODE_HANDLE_CODE_EXPANDED = '<span class="' + CLASS_JQUERY_TREE_HANDLE + ' badge">' + TREE_NODE_HANDLE_EXPANDED + '</span>';


	$.fn.extend({
		/**
		 * Делает дерево из структуры вида:
		 * <ul>
		 *   <li><label><input type="checkbox" />Item1</label></li>
		 *   <li>
		 *     <label><input type="checkbox" />ItemWithSubitems</label>
		 *     <ul>
		 *       <li><label><input type="checkbox" />Subitem1</label></li>
		 *     </ul>
		 *   </li>
		 * </ul>
		 */
		Tree: function() {
			$(this).each(function() {
				if (this.Tree) {
					return this;
				}
				var self = this;
				//var controls = $(TREE_CONTROLS_CODE);

				// Добавим контролы для всего дерева (все свернуть, развернуть и т.д.), и добавим класс
				$(this)
						.addClass(CLASS_JQUERY_TREE)
						.before(TREE_CONTROLS_CODE);
				//.prev('.' + CLASS_JQUERY_TREE_CONTROLS)
				//console.log($(this));
				var controls = $(this).prev('.' + CLASS_JQUERY_TREE_CONTROLS);

				$('.' + CLASS_JQUERY_TREE_COLLAPSE_ALL, controls).click(function() {
					$('li:has(ul)', self)
							.addClass(CLASS_JQUERY_TREE_COLLAPSED)
							.find('.' + CLASS_JQUERY_TREE_HANDLE)
							.html(TREE_NODE_HANDLE_COLLAPSED);
				});

				$('.' + CLASS_JQUERY_TREE_EXPAND_ALL, controls)
						.click(function() {
					$('li:has(ul)', self)
							.removeClass(CLASS_JQUERY_TREE_COLLAPSED)
							.find('.' + CLASS_JQUERY_TREE_HANDLE)
							.html(TREE_NODE_HANDLE_EXPANDED);
				});

				$('li', this).find(':first').addClass(CLASS_JQUERY_TREE_TITLE)
						.closest('li').addClass(CLASS_JQUERY_TREE_LEAF);

				$('.' + CLASS_JQUERY_TREE_SEARCH, controls).keydown(function(e) {
					if (e.which == 13) {
						e.preventDefault();
						e.stopPropagation();
						e.stopImmediatePropagation();
						$('.' + CLASS_JQUERY_TREE_SEARCH_BTN, controls).click();
					}
				});
				$('.' + CLASS_JQUERY_TREE_SEARCH_BTN, controls).click(function() {
					var search_words = $('.' + CLASS_JQUERY_TREE_SEARCH, controls).val().trim();
					if (search_words.indexOf('|') == -1)
						search_words = search_words.split(' ').join('|');
					else
						search_words = search_words.replace('|', '\\\|');
					if (search_words.length == 0)
						return;
					$(self).find('.' + CLASS_JQUERY_TREE_HANDLE).removeClass('badge-info');
					$(self).find('label')
							.removeClass(CLASS_JQUERY_TREE_FOUNDED)
							.each(function() {
						if ($(this).html().search(new RegExp('(' + search_words + ')', 'i')) != -1) {
							$(this).addClass(CLASS_JQUERY_TREE_FOUNDED)
									.parents('li')
									.removeClass(CLASS_JQUERY_TREE_COLLAPSED)
									.children('span')
									.addClass('badge-info')
									.html(TREE_NODE_HANDLE_EXPANDED);
						}

					});
				});

				$('.' + CLASS_JQUERY_TREE_SEARCH_CLEAR_BTN, controls).click(function() {
					$('.' + CLASS_JQUERY_TREE_SEARCH, controls).val('');
					$(self).find('label')
							.removeClass(CLASS_JQUERY_TREE_FOUNDED);
					$(self).find('.' + CLASS_JQUERY_TREE_HANDLE).removeClass('badge-info');
				});

				// Для всех элементов, являющихся узлами (имеющих дочерние элементы)...
				var peers = $('li:has(ul)', this)//.find(':first')
						// ... добавим элемент, открывающий/закрывающий узел
						//.before(TREE_NODE_HANDLE_CODE)
						// ... добавим к контейнеру класс "узел дерева" и "свернем".
						.closest('li');
				peers.addClass(CLASS_JQUERY_TREE_NODE)
						//.addClass(CLASS_JQUERY_TREE_COLLAPSED)
						.removeClass(CLASS_JQUERY_TREE_LEAF);

				peers.each(function() {
					var element = $(this);
					if (element.data('expanded') != true) {
						element.addClass(CLASS_JQUERY_TREE_COLLAPSED)
								// ... добавим элемент, открывающий/закрывающий узел
								.prepend(TREE_NODE_HANDLE_CODE_COLLAPSED)
					}
					else {
						element.prepend(TREE_NODE_HANDLE_CODE_EXPANDED)
					}
				});

				// ... повесим обработчик клика
				$('.' + CLASS_JQUERY_TREE_HANDLE + ', label', this).bind('click', function() {
					if ($(event.target).hasClass('noprevent'))
						return;
					var leafContainer = $(this).parent('li');
					var leafHandle = leafContainer.find('>.' + CLASS_JQUERY_TREE_HANDLE);

					leafContainer.toggleClass(CLASS_JQUERY_TREE_COLLAPSED);

					if (leafContainer.hasClass(CLASS_JQUERY_TREE_COLLAPSED))
						leafHandle.html(TREE_NODE_HANDLE_COLLAPSED);
					else
						leafHandle.html(TREE_NODE_HANDLE_EXPANDED);
				});

				// Добавляем обработку клика по чекбоксам
				$('input:checkbox', this).click(function() {
					setLabelClass(this);
					checkCheckbox(this);
				})
						// Выставляем чекбоксам изначальные классы
						.each(function() {
					setLabelClass(this);
					if (this.checked)
						checkParentCheckboxes(this);
				})
						// Для IE вешаем обработчики на лейбл
						.closest('label').click(function() {
					labelClick(this);
					checkCheckbox($('input:checkbox', this));
				});
			});
		}
	});

	/**
	 * Рекурсивно проверяет, все ли чекбоксы в поддереве родительского узла выбраны.
	 * Если ни один чекбокс не выбран - снимает чек с родительского чекбокса
	 * Если хотя бы один, но не все - выставляет класс CLASS_JQUERY_TREE_CHECKED_PARTIAL родительскому чекбоксу
	 * Если все - ставит чек на родительский чекбокс
	 *
	 * @param {Object} checkboxElement текущий чекбокс
	 */
	function checkParentCheckboxes(checkboxElement) {
		if (typeof checkboxElement == 'undefined' || !checkboxElement)
			return;

		// проверим, все ли чекбоксы выделены/частично выделены на вышележащем уровне
		var closestNode = $(checkboxElement).closest('ul');
		var allCheckboxes = closestNode.find('input:checkbox');
		var checkedCheckboxes = closestNode.find('input:checkbox:checked');

		var allChecked = allCheckboxes.length == checkedCheckboxes.length;

		var parentCheckbox = closestNode.closest('li').find('>.' + CLASS_JQUERY_TREE_TITLE + ' input:checkbox');

		if (parentCheckbox.length > 0) {
			parentCheckbox.get(0).checked = allChecked;

			if (!allChecked && checkedCheckboxes.length > 0)
				parentCheckbox.closest('label')
						.addClass(CLASS_JQUERY_TREE_CHECKED_PARTIAL)
						.removeClass(CLASS_JQUERY_TREE_CHECKED)
						.removeClass(CLASS_JQUERY_TREE_UNCHECKED);
			else
			if (allChecked)
				parentCheckbox.closest('label')
						.removeClass(CLASS_JQUERY_TREE_CHECKED_PARTIAL)
						.removeClass(CLASS_JQUERY_TREE_UNCHECKED)
						.addClass(CLASS_JQUERY_TREE_CHECKED);
			else
				parentCheckbox.closest('label')
						.removeClass(CLASS_JQUERY_TREE_CHECKED_PARTIAL)
						.removeClass(CLASS_JQUERY_TREE_CHECKED)
						.addClass(CLASS_JQUERY_TREE_UNCHECKED);

			checkParentCheckboxes(parentCheckbox.get(0));
		}
	}

	/**
	 * Если у текущего чекбокса есть дочерние узлы - меняет их состояние
	 * на состояние текущего чекбокса
	 *
	 * @param {Object} checkboxElement текущий чекбокс
	 */
	function checkCheckbox(checkboxElement) {
		// чекнем/анчекнем нижележащие чекбоксы
		$(checkboxElement).closest('li').find('input:checkbox').each(function() {
			this.checked = $(checkboxElement).attr('checked');
			setLabelClass(this);
		});
		checkParentCheckboxes(checkboxElement);
	}
	;

	/**
	 * Выставляет класс лейблу в зависимости от состояния чекбокса
	 *
	 * @param {Object} checkboxElement чекбокс
	 */
	function setLabelClass(checkboxElement) {
		isChecked = $(checkboxElement).attr('checked');

		if (isChecked) {
			$(checkboxElement).closest('label')
					.addClass(CLASS_JQUERY_TREE_CHECKED)
					.removeClass(CLASS_JQUERY_TREE_UNCHECKED)
					.removeClass(CLASS_JQUERY_TREE_CHECKED_PARTIAL);
		}
		else {
			$(checkboxElement).closest('label')
					.addClass(CLASS_JQUERY_TREE_UNCHECKED)
					.removeClass(CLASS_JQUERY_TREE_CHECKED)
					.removeClass(CLASS_JQUERY_TREE_CHECKED_PARTIAL);
		}
	}
	;

	/**
	 * Обрабатывает клик по лейблу (для IE6)
	 */
	function labelClick(labelElement) {
		var checkbox = $('input:checkbox', labelElement);
		var checked = checkbox.attr('checked');
		checkbox.attr('checked', !checked);
		setLabelClass(checkbox);
	}

})(jQuery);
