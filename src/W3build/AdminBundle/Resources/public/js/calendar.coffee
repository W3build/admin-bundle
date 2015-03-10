class Calendar

  @SHOW_TYPE_MONTH = 'moth';
  @SHOW_TYPE_WEEK = 'week';
  @SHOW_TYPE_YEAR = 'year';
  @START_DAY_MONDAY = 'monday';
  @START_DAY_SUNDAY = 'sunday';

  _defaultOptions = {
    dayStates: {},
    showType: @SHOW_TYPE_MONTH,
    startDay: @START_DAY_MONDAY,
    startDate: new Date(),
    clickCallback: null,
    showMonthName: true,
    canChangeMonth: true,
    selectCurrent: true,
    selectedDay: null,
    dayNames:
      0: "Neděle",
      1: "Pondělí",
      2: "Úterý",
      3: "Středa",
      4: "Čtvrtek",
      5: "Pátek",
      6: "Sobota"
    monthNames:
      0: 'Leden',
      1: 'Unor',
      2: 'Březen',
      3: 'Duben',
      4: 'Květen',
      5: 'Červen',
      6: 'Červenec',
      7: 'Srpen',
      8: 'Září',
      9: 'Říjen',
      10: 'Listopad',
      11: 'Prosinec'
  }

  _dayOrder = {
    monday: [1, 2, 3, 4, 5, 6, 0],
    sunday: [0, 1, 2, 3, 4, 5, 6]
  }

  constructor: (element, options = {}) ->
    @element = $(element);
    @options = _defaultOptions
    $.extend(@options, options)
    if @options.selectedDay
      @options.selectedDay = new Date(@options.selectedDay);
      @options.startDate = @options.selectedDay;
    if @options.selectedDay is null and @options.selectCurrent
      @options.selectedDay = new Date()
    _render.call @

  setDayStates: (dayStates = {}) ->
    if dayStates
      @options.dayStates = dayStates;

  _render = () ->
    switch @options.showType
      when Calendar.SHOW_TYPE_MONTH then _renderMonth.call @
      when Calendar.SHOW_TYPE_WEEK then _renderWeek.call @
      when Calendar.SHOW_TYPE_YEAR then _renderYear.call @
      else throw 'Unknow render type'

  _renderMonth = () ->
    firstDay = new Date(@options.startDate.getFullYear(), @options.startDate.getMonth(), 1);
    firstDayOfMonthNumber = firstDay.getDay();
    lastDay = new Date(@options.startDate.getFullYear(), @options.startDate.getMonth() + 1, 0);
    lastDayOfMonthNumber = lastDay.getDay();

    switch @options.startDay
      when Calendar.START_DAY_MONDAY
        if firstDayOfMonthNumber is 0
          back = 6;
        else
          back = firstDayOfMonthNumber - 1
        if lastDayOfMonthNumber isnt 0
          plus = 7 - lastDayOfMonthNumber
      when Calendar.START_DAY_SUNDAY
        back = firstDayOfMonthNumber - 1
        plus = 6 - lastDayOfMonthNumber
      else throw 'Unknow start day type'

    if back
      firstDay.setDate(firstDay.getDate()-back);
    if plus
      lastDay.setDate(lastDay.getDate()+plus);

    renderedDay = firstDay;
    lastDay.setDate(lastDay.getDate()+1);

    _empty.call @

    _renderMonthHeaders.call @

    if @options.showMonthName is false
      $('.customCalendarHolder .monthName').hide()
    if @options.canChangeMonth is false
      $('.customCalendarHolder .prevMonth').hide()
      $('.customCalendarHolder .nextMonth').hide()

    currentDate = new Date();
    week = 1
    _createWeek.call @, 1
    remainigDayInWeek = 7;
    while renderedDay < lastDay
      specific = ''
      if new Date(renderedDay.getFullYear(),renderedDay.getMonth()) < new Date(@options.startDate.getFullYear(), @options.startDate.getMonth())
        specific += ' prev'
      if new Date(renderedDay.getFullYear(),renderedDay.getMonth()) > new Date(@options.startDate.getFullYear(), @options.startDate.getMonth())
        specific += ' next'
      if _getFormatedDate(renderedDay) is _getFormatedDate(currentDate)
        specific += ' current'
      if _getFormatedDate(@options.selectedDay) is _getFormatedDate(renderedDay)
        specific += ' selected'
      _renderDay.call @, renderedDay, week, specific
      renderedDay.setDate(renderedDay.getDate() + 1)
      remainigDayInWeek -= 1;
      if remainigDayInWeek is 0
        week += 1;
        remainigDayInWeek = 7;
        _createWeek.call @, week

  _createWeek = (number) ->
    $('.customCalendar tbody', @element).append('<tr class="week_' + number + '"></tr>');

  _getFormatedDate = (date) ->
    return date.getFullYear() + '-' + ('0' + (date.getMonth()+1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2)

  _renderDay = (day, week, specific = '') ->
    formatedDay = _getFormatedDate(day)
    specific = if typeof @options.dayStates[formatedDay] is 'undefined' then specific else specific + ' ' + @options.dayStates[formatedDay];
    $('.customCalendar tbody tr.week_' + week, @element).append('<td class="day' + specific + '" data-date="' + formatedDay + '">' + day.getDate() + '</td>');
    _this = @
    $('.customCalendarHolder .day').unbind('click').bind 'click', (e) ->
      e.preventDefault();

      _this.options.selectedDay = new Date($(this).attr('data-date'));
      if $(this).hasClass('prev')
        _getPrevMonth.call _this
      else
        if $(this).hasClass('next')
          _getNextMonth.call _this
        else
          _render.call _this

      if typeof _this.options.clickCallback is 'function'
        _this.options.clickCallback(_getFormatedDate(_this.options.selectedDay));

      return false;

  _empty = () ->
    @element.empty();
    @element.append('<div class="customCalendarHolder"></div>')
    $('.customCalendarHolder', @element).append('<table class="customCalendar"><thead><tr></tr></thead><tbody></tbody></table>')

  _getNextMonth = () ->
    @options.startDate = new Date(@options.startDate.getFullYear(), @options.startDate.getMonth() + 1, 1);
    _renderMonth.call @

  _getPrevMonth = () ->
    @options.startDate = new Date(@options.startDate.getFullYear(), @options.startDate.getMonth() - 1, 1);
    _renderMonth.call @

  _renderMonthHeaders = () ->
    $('.customCalendar thead', @element).append('<tr><th class="prevMonth"><<</th><th colspan="5" class="monthName">' + @options.monthNames[@options.startDate.getMonth()] + ' ' + @options.startDate.getFullYear() + '</th><th class="nextMonth">>></th></tr>');
    $('.customCalendarHolder .prevMonth').unbind('click').bind 'click', (e) =>
      e.preventDefault();

      _getPrevMonth.call @

      return false;
    $('.customCalendarHolder .nextMonth').unbind('click').bind 'click', (e) =>
      e.preventDefault();

      _getNextMonth.call @

      return false;

    $('.customCalendar thead ', @element).append('<tr class="dayNames"></tr>');
    for dayNumber in _dayOrder[@options.startDay]
      $('.customCalendar thead .dayNames', @element).append('<th>' + @options.dayNames[dayNumber] + '</th>');

$ = jQuery

$.fn.extend
  customCalendar: (options = {}) ->
    customCalendar = $.data(this[0], 'customCalendar');
    if customCalendar
      return customCalendar

    customCalendar = new Calendar(this[0], options)
    $.data(this[0], 'customCalendar', customCalendar);
    return customCalendar;